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
            $user = Utility::locale($data->lng);
            \Cookie::queue('lang', $user['language'], 86400 * 60);
        }
        return [
            'lng' => \App\Lang::where('lng', $user['language'])->get(['name','text'])->mapWithKeys(function ($item) {
                return [$item['name'] => $item['text']];
            }), 
            'currency' => Currency::where('name', $user['currency'])->orderBy('date','desc')->first()
        ];
    }
    public function update(Request $data)
    {
        $user = Auth::user();
        $validation = Validator::make($data->all(), [
            'user.name' => 'required|min:3|max:255',
            'user.tel' => 'required|min:3|max:11'
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
    public function toWish(Request $data)
    {
        $user_id = Auth::id();
        $prod = Product::with(['wish' => function ($query) use ($user_id) {
            $query->where('user_id',$user_id);
        }])->where('id',$data->id)->first();

        if(!empty($prod->wish)) {
            $prod->wish->delete();
            return 0;
        } else {
            $date = new \DateTime;
            $user_wish =  new UserWishes;
            $user_wish->user_id = $user_id;
            $user_wish->product_id = $data->id;
            $user_wish->price = $prod->price;
            $user_wish->isAvailable = $prod->available>0?1:0;
            $user_wish->date = $date->format("Y-m-d");
            $user_wish->save();
            return 1;
        }
    }
    public function rate(Request $request){
        $request->validate([
            'id' => 'required|exists:products,id',
            'rating' => 'required|numeric|min:0|max:5'
        ]);
        $user_id = Auth::id();
        $product = Product::find($request->id);
        $userProductRating = \App\UserProductRating::where('user_id', $user_id)->where( 'product_id', $request->id)->first();
        if(empty($userProductRating)) {
            $userProductRating = new \App\UserProductRating;
            $product->vote_count++;
        }
        $product->rating = (($product->vote_count-1) * $product->rating + $request->rating) / $product->vote_count;
        $product->save();
        $userProductRating->user_id = $user_id; 
        $userProductRating->product_id = $request->id; 
        $userProductRating->rating = $request->rating; 
        $userProductRating->save();
        // \App\UserProductRating::updateOrCreate(
        //     ['user_id' => $user_id, 'product_id' => $request->id],
        //     ['user_id' => $user_id, 'product_id' => $request->id, 'rating' => $request->rating]
        // );
    }
    public function auth(Request $request)
    {
        if(!empty($request->header('Authorization')) && preg_match('/Bearer\s(\S+)/', $request->header('Authorization'), $matches)) {
            $token = $matches[1];
        } else return response(null, 401);

        try {
            $header_payload_signature_array = explode('.', $token);
            $header = json_decode(JWT::urlsafeB64Decode($header_payload_signature_array[0]));
            $payload = json_decode(JWT::urlsafeB64Decode($header_payload_signature_array[1]));
        
            if(empty($payload->email)) throw new \Exception('Email claim mismatch');
            $email = $payload->email;
            if($payload->aud != env('JWT_AUD')) throw new \Exception('Audience (aud) claim mismatch');
        }
        catch (\Exception $e) {
            return response($e->getMessage(), 401);
        }
        
        $user = User::where('email', $email)->first();

        try {
            $certs = json_decode(file_get_contents('https://www.googleapis.com/robot/v1/metadata/x509/securetoken@system.gserviceaccount.com'));            
            
            foreach ($certs as $key => $cert) {
                if($key==$header->kid) break;
            }

            $pub_key = openssl_pkey_get_public($cert); 
            $keyData = openssl_pkey_get_details($pub_key); 
            $decoded = JWT::decode($token, $keyData['key'], array('RS256'));
        } catch (\Exception $e) {
            return response($e->getMessage(), 401);
        }

        if(!$user && !empty($decoded->name))
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
        if(!Auth::loginUsingId($user->id, true)) return response(null, 401);
    }
    public function mail(Request $data) {
        $states = [255, 127,63,31,15,7,3,1]; //is User subscribed to receive email updates
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
                    'img_src' => "/file/".$wish->prod->img_src
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