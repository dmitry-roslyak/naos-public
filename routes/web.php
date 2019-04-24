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
use Illuminate\Support\Str;
use Illuminate\Http\Request;

Route::get('/', function (Request $data) {
    return view('vue');
});
Route::get('/lang/{lng}', 'UserController@lang');

Route::group(['middleware' => 'admin'], function() {
    Route::get('/token', 'DashboardController@tokenRefresh');
    
    Route::post('/upload', 'DashboardController@productCreate');
    Route::get('/t1', 'DashboardController@tg');
    Route::get('/discount_list', function () {
        return App\Discount::get();
    });
    Route::get('/mail_user_test', 'UserController@mail');
});

Route::post('/search', 'ProductController@search');
Route::get('/prod_filter', 'ProductController@filterBy');
Route::get('/prod_by_id', 'ProductController@show_one');
Route::get('/prodsby_ids', 'ProductController@show_many');
Route::get('/products_with_discount_by_ids', 'ProductController@withDiscountShowByIds');
Route::get('/prod_history', 'ProductController@history');
Route::get('/prod_rnd', 'ProductController@rnd');

Route::get('/all_comments', 'CommentController@show');
Route::post('/order', 'OrderController@store');
Route::post('/auth', 'UserController@auth');

Route::get('/ctg_spec', function (Request $data) {
    return App\Product::with('specs')->where('category_id', $data->id)->get()->first();
});
Route::get('/get_filters', function (Request $data) {
     return App\Filter::with('values')->where('category_id', $data->id)->get();
});

Route::group(['middleware' => ['auth']], function () {
    Route::post('/leave_comment', 'CommentController@store');
    Route::get('/comment_like', 'CommentController@like');
    Route::post('/to_wish', 'UserController@toWish');
    Route::get('/product_rate', 'UserController@rate');
    Route::get('/user_info', 'UserController@info');
    Route::post('/update_user_info', 'UserController@update');
    Route::post('/user_comments_like', 'UserController@likes');
});
Route::get('/set_currency', 'UserController@currency');

Auth::routes();