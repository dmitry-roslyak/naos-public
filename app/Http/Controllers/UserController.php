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
use Auth;
use \Firebase\JWT\JWT;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use App\Mail\Mailer;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function updinfo(Request $data)
    {
        $user = Auth::user();
        if($user->fname != $data->user['fname'] && $data->user['fname']) $user->fname = $data->user['fname'];
        if($user->lname != $data->user['lname'] && $data->user['lname']) $user->lname = $data->user['lname'];
        if($user->tel != $data->user['tel'] && $data->user['tel']) $user->tel = $data->user['tel'];
        if($user->adr != $data->user['adr'] && $data->user['adr']) $user->adr = $data->user['adr'];
        $user->save();
    }
    public function info(Request $data)
    {
        return Auth::user();
    }
    public function likes(Request $data)
    {
        $user_id = Auth::id();
        $comm_id = Comment::where('product_id',$data->id)->get(['id']);
        return UserCommentsLike::whereIn('comment_id',$comm_id)->where('user_id',$user_id)->get();
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
                if(!empty($_COOKIE['lang'])) $array[0] = $_COOKIE['lang'];
                else if(!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
                    $array = explode(',', str_replace(";",',', $_SERVER['HTTP_ACCEPT_LANGUAGE']));
                } 
            
                $lang = 'en'; $currency = 'USD';
            
                for ($index = 0; $index < count($array); $index++) {
                    if($array[$index] == 'ru' || $array[$index] == 'ru-RU') { $lang = 'ru'; $currency = 'RUB'; break; }
                    else if($array[$index] == 'uk' || $array[$index] == 'ua') { $lang = 'ua'; $currency = 'UAH'; break; }
                }
                
                $user = new User;
                $user->name = $decoded->name;
                $user->currency = $currency;
                $user->language = $lang;
                $user->email = $email;
                $user->password = Uuid::uuid4();
                $user->save();
                $user_id = $user->id;
            }
            else $user_id = $user->id;
            if(Auth::loginUsingId($user_id, true)) return response(null,200);
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
        // $encrypted = Crypt::encryptString('Hello world.');
        // return Crypt::decryptString($encrypted);
        // return bcrypt(env('KEY1', false).'4895142232120006');
        //   $value = file_get_contents('https://openexchangerates.org/api/latest.json?app_id=9d63c3fdce5f4b218824682ec539a810');
        //   return json_decode($value)->rates->UAH;
    }
}