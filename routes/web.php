<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image1;
use Illuminate\Support\Facades\Crypt;

use Illuminate\Support\Facades\Mail;
use App\Mail\Mailer;
Route::domain('dashboard.localhost')->group(function () {
    Route::get('/', function () {
        return view('dashboard');
    });
});
Route::post('/upload', 'DashBoardController@imgUpload');
Route::get('/t1', 'DashBoardController@tg');
Route::get('/ctg_spec', function (Request $data) {
    return App\Product::with('specs')->where('category_id', $data->id)->get()->first();
});
Route::get('/discount_list', function () {
    return App\Discount::get();
});

Route::get('/', 'HomeController@index');
Route::get('/lang/{lng}', 'HomeController@lang');
Route::get('/set_currency', 'HomeController@currency');

Route::post('/search', 'ProductController@search');
Route::get('/prod_filter', 'ProductController@filterBy');
Route::get('/prod_by_id', 'ProductController@show_one');
Route::get('/prodsby_ids', 'ProductController@show_many');
Route::get('/prod_history', 'ProductController@history');
Route::get('/prod_rnd', 'ProductController@rnd');
Route::post('/to_wish', 'ProductController@toWish');

Route::get('/user_info', 'UserController@info');
Route::post('/update_user_info', 'UserController@updinfo');
Route::post('/user_comments_like', 'UserController@likes');

Route::post('/leave_comment', 'CommentController@store');
Route::get('/all_comments', 'CommentController@show');
Route::get('/comment_like', 'CommentController@like');

Route::post('/order', 'OrderController@store');

Route::post('/auth', 'UserController@auth');

Route::get('/file/{filename}', function ($filename) {
    return Storage::get($filename);
})->where('filename', '^[^/]+$');

Route::get('/get_filters', function (Request $data) {
     return App\Filter::with('values')->where('category_id', $data->id)->get();
});

Route::get('/ex', function (Request $data) {
    return $value = file_get_contents('https://openexchangerates.org/api/latest.json?app_id=9d63c3fdce5f4b218824682ec539a810');
    
});
Route::get('/init_filters', function (Request $data) {
    $categories = App\Product::get(['id','category_id'])->groupBy('category_id');

    foreach ($categories as $category_id => $category) {
        $product_id = [];
        $filters = [];
        foreach ($category as $product) {
           $product_id[] = $product->id;
        }
        $spec = App\Spec::whereIn('prod_id',$product_id)->where('isFilterable','1')->get(['prod_id','name','value'])->groupBy('name');
        foreach ($spec as $name => $value) {
            $val = $value->groupBy('value');
            $vvs = [];
            foreach ($val as $value => $arr) {
                $ids = [];
                foreach ($arr as $key => $v1) {
                    array_push($ids,['product_id'=>$v1->prod_id]);
                }
                array_push($vvs,[['priority'=>2,'popularity'=>0,'value'=> $value],$ids]);
            }
            $temp  = App\Filter::updateOrCreate(
                ['category_id'=> $category_id,'name'=> $name,'desc'=> 'some text']
            );
            
            foreach ($vvs as $key => $v2) {
                $filter_value = App\FilterValue::with('prod_ids')->where('filter_id',$temp->id)->where('value',$v2[0]['value'])->first();

                if($filter_value){
                    if(count($filter_value->prod_ids)){
                        foreach ($v2[1] as $value) {
                            if(!$filter_value->prod_ids->contains('product_id', $value['product_id'])){
                                App\FilterValueProduct::create([
                                    'filter_value_id' => $filter_value->id,
                                    'product_id' => $value['product_id']
                                ]);
                            }
                        }
                    }
                    else $filter_value->prod_ids()->createMany($v2[1]);
                }
                else{
                    App\FilterValue::create((function($temp,$v3){
                        return ['filter_id'=>$temp->id,'priority'=>$v3['priority'],
                        'popularity'=> $v3['popularity'],'value'=>$v3['value']];
                    })($temp,$v2[0]))->prod_ids()->createMany($v2[1]);
                }
            }
        }
    }
    return 'done';
});

Route::get('/test', function () {
    $states = [127,63,31,15,7,3,1]; //is User subscribed to receive email updates
    $usr = App\User::with('wishes','crnc')->whereIn('bstate', $states)->get();

    $count = count($usr);
    for ($i=0; $i < $count; $i++) {
        if (isset($usr[$i]->wishes)) {
            $products = [];
            foreach ($usr[$i]->wishes as $wish) {
                // if(!$wish->isAvailable && $wish->prod->available > 0 ){
                if(true){
                    array_push($products, [
                        'type' => 3,
                        'id' => $wish->prod->id,
                        'name' => $wish->prod->name,
                        'old_price' => 0,
                        'price' => $wish->prod->price * $usr[$i]->crnc->rate,
                        'img_src' => $_SERVER['APP_URL']."/file/".$wish->prod->img_src
                    ]);
                }
                else if ($wish->prod->price < $wish->price && $wish->price - $wish->prod->price >= $wish->prod->price/100*3) {
                    array_push($products, [
                        'type' => 1,
                        'id' => $wish->prod->id,
                        'name' => $wish->prod->name,
                        'old_price' => $wish->price * $wish->crnc->where('name',$usr[$i]->crnc->name)->first()->rate,
                        'price' => $wish->prod->price * $usr[$i]->crnc->rate,
                        'img_src' => $_SERVER['APP_URL']."/file/".$wish->prod->img_src
                    ]);
                }
                // $wish->price = $wish->prod->price;
                // $wish->save();
            }
            if($usr[$i]->language=='ru')
                return view('ru-mail')->with([
                    'user' => $usr[$i]->name,
                    'currency' => $usr[$i]->crnc->name,
                    'products' => $products
                ]);
            else if($usr[$i]->language=='en')
                return view('en-mail')->with([
                    'user' => $usr[$i]->name,
                    'currency' => $usr[$i]->crnc->name,
                    'products' => $products
                ]);
            // Mail::to($notify[$i][0]->user->email,'test')->send(new Mailer($message));
        }
    }
    // $encrypted = Crypt::encryptString('Hello world.');
    // return Crypt::decryptString($encrypted);
    // return env('KEY1', false);
    // return bcrypt(env('KEY1', false).'4895142232120006');
    //   $value = file_get_contents('https://openexchangerates.org/api/latest.json?app_id=9d63c3fdce5f4b218824682ec539a810');
    //   return json_decode($value)->rates->UAH;
});
Auth::routes();