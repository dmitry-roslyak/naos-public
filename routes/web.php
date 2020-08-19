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

Route::get('/products', 'ProductController@productsByIds');
Route::get('/products/random', 'ProductController@rnd');
Route::get('/products/filter', 'ProductController@filterBy');
Route::get('/products/search/{name}', 'ProductController@search');
Route::get('/product', 'ProductController@show_one');
Route::get('/product/history', 'ProductController@history');

Route::get('/comments', 'CommentController@show');
Route::post('/order', 'OrderController@store');
Route::post('/auth', 'UserController@auth');

Route::get('/filters', function (Request $data) {
     return App\Filter::with('values')->where('category_id', $data->category_id)->get();
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

Route::get('/lang/{lng}', 'UserController@lang');
Route::get('/set_currency', 'UserController@currency');

Route::group(['middleware' => 'admin'], function() {
    Route::get('/token', 'DashboardController@tokenRefresh');
    
    Route::post('/upload', 'DashboardController@productCreate');
    Route::get('/t1', 'DashboardController@tg');
    Route::get('/discount_list', function () {
        return App\Discount::get();
    });
    Route::get('/mail_user_test', 'UserController@mail');
});

Auth::routes();
Route::view('/logout', 'auth.logout');

Route::get('/{vue?}', function() {
    return view('vue');
})->where('vue', '.*');
