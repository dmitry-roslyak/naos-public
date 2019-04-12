<?php

 namespace App\Http\Controllers;
//  namespace App;

use Illuminate\Http\Request;
use App\Product;
use App\WishProduct;
use App\UserWishes;
use App\UserCommentsLike;
use App\User;
use App\Spec;
use App\Comment;
use App\Currency;

use Auth;
use \Firebase\JWT\JWT;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use App\Mail\Mailer;
use App\Http\Traits\Utility;

use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function currency(Request $data)
    {
        $validation = Validator::make($data->all(), [
            'val' => 'required|exists:currencies,name',
        ]);
        if($validation->fails()) return response($validation->errors(), 422);
        $user = Auth::user();
        $user->currency = $data->val;
        $user->save();
        return Currency::where('name', $user->currency)->orderBy('date','desc')->first();
    }
    public function lang(Request $data)
    {
        $validation = Validator::make(['lng' => $data->lng], [
            'lng' => 'required|exists:langs,lng',
        ]);
        if($validation->fails()) return response($validation->errors(), 422);
        $user = Auth::user();
        if($user) {
            $user->language = $data->lng;
            $user->save();
        } else {
            $user = Utility::locale([ 0 => $data->lng ]);
            \Cookie::queue('lang', $user['language'], 86400 * 60);
        }
        return [
            \App\Lang::where('lng', $user['language'])->get(['name','text'])->mapWithKeys(function ($item) {
                return [$item['name'] => $item['text']];
            }), 
            Currency::where('name', $user['currency'])->orderBy('date','desc')->first()
        ];
    }
    public function updinfo(Request $data)
    {
        $user = Auth::user();
        $validation = Validator::make($data->all(), [
            'user.name' => 'required|min:3',
            'user.tel' => 'required|numeric|min:3'
        ]);
        if($validation->fails()) return response($validation->errors(), 422);
        $user->update(collect($data->user)->only(['name', 'tel'])->all());
    }
    public function info(Request $data)
    {
        return Auth::user();
    }
    public function likes(Request $data)
    {
        $comm_id = Comment::where('product_id',$data->id)->get(['id']);
        return UserCommentsLike::whereIn('comment_id',$comm_id)->where('user_id', Auth::id())->get();
    }
    public function auth(Request $data)
    {
            // $jwk_set = JWKFactory::createFromX5U('https://www.googleapis.com/robot/v1/metadata/x509/securetoken@system.gserviceaccount.com');
            // $loader = new Loader();
            // $jws = $loader->loadAndVerifySignatureUsingKeySet(
            //     $jwt_token,
            //     $jwk_set,
            //     ['RS256'],
            //     $null
            // );
            $jwt_token = $data->input;
            if(!$jwt_token) return response(null,401);

            $certs = json_decode(file_get_contents ('https://www.googleapis.com/robot/v1/metadata/x509/securetoken@system.gserviceaccount.com'));
            $kid = json_decode(JWT::urlsafeB64Decode(explode('.',$jwt_token)[0]))->kid;
            
            foreach ($certs as $key => $cert) {
                if($key==$kid) break;
            }
            $pub_key = openssl_pkey_get_public($cert); 
            $keyData = openssl_pkey_get_details($pub_key); 
            
            $decoded = JWT::decode($jwt_token, $keyData['key'], array('RS256'));

            if($decoded->aud!='dev-naos') response(null,400);
            $email = $decoded->email;
            $user = User::where('email',$email)->first();
            
            if(!$user)
            {
                $locale = Utility::locale();
                
                $user = new User;
                $user->name = $decoded->name;
                $user->currency = $locale['currency'];
                $user->language = $locale['language'];
                $user->email = $email;
                $user->password = Uuid::uuid4();
                $user->save();
            }
            if(Auth::loginUsingId($user->id, true)) return response(null,200);
            else return response(null,401);
    }
    public function mail(Request $data) {
        $states = [127,63,31,15,7,3,1]; //is User subscribed to receive email updates
        $users = User::with('wishes')->whereIn('bstate', $states)->get();

        foreach ($users as $key => $user) {
            if (!count($user->wishes)) continue;
            $currency = \App\Currency::where('name', $user->currency)->orderBy('date','desc')->first();
            $products = [];

            foreach ($user->wishes as $wish) {
                if(!$wish->isAvailable && $wish->prod->available > 0)
                    $type = 3;
                else if($wish->price - $wish->prod->price >= $wish->price/100*5)
                    $type = 1;
                else continue;
                array_push($products, [
                    'type' => $type,
                    'id' => $wish->prod->id,
                    'name' => $wish->prod->name,
                    'old_price' => $wish->price * $currency->rate,
                    'price' => $wish->prod->price * $currency->rate,
                    'img_src' => env("APP_URL")."/file/".$wish->prod->img_src
                ]);
                // $wish->price = $wish->prod->price;
                // $wish->save();
            }
            \App::setLocale($user->language);
            // return view('mail')->with([
            //     'user' => $user->name,
            //     'currency' => $currency->name,
            //     'products' => $products
            // ]);
            Mail::to($user->email)->send(new Mailer($user, $products));
        }
    }
}