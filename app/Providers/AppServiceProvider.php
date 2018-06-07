<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('userlang', function ($expression) {
            $user_id = \Auth::id();
            if($user_id){
                $lang = App\User::find($user_id)->language;
            }
            else if(isset($_COOKIE['lang']))
            {
                $lang = $_COOKIE['lang'];
                if($lang == 'RU' || $lang == 'ru' || $lang == 'ru-RU') $lang = 'ru';
                else if($lang == 'UA' || $lang == 'ua' || $lang == 'ua-UA') $lang = 'ua';
                else $lang = 'en';
            }
            else{
                $temp = str_replace(";",',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
                $temp2 = explode(',',$temp);
                for ($index = 0; $index < count($temp2); $index++) {
                    if($temp2[$index] == 'ru' || $temp2[$index] == 'ru-RU') $lang = 'ru';
                    else if($temp2[$index] == 'en' || $temp2[$index] == 'en-US'|| $temp2[$index] == 'en-UK') $lang = 'en';
                    else if($temp2[$index] == 'ua') $lang = 'ua';
                }
            }
            return $lang;
        });
        Blade::directive('usercurrency', function () {
            $user_id = \Auth::id();
            if($user_id){
                $currency = App\User::find($user_id)->currency;
            }
            else if(isset($_COOKIE['lang']))
            {
                $lang = $_COOKIE['lang'];
                if($lang == 'RU' || $lang == 'ru' || $lang == 'ru-RU') $currency = 'RUB';
                else if($lang == 'UA' || $lang == 'ua' || $lang == 'ua-UA') $currency = 'UAH';
                else $currency = 'USD';
            }
            else{
                $temp = str_replace(";",',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
                $temp2 = explode(',',$temp);
                for ($index = 0; $index < count($temp2); $index++) {
                    if($temp2[$index] == 'ru' || $temp2[$index] == 'ru-RU') $currency = 'RUB';
                    else if($temp2[$index] == 'en' || $temp2[$index] == 'en-US'|| $temp2[$index] == 'en-UK') $currency = 'USD';
                    else if($temp2[$index] == 'ua') $currency = 'UAH';
                }
            }
            return json_encode( \App\Currency::where('name', $currency)->orderBy('date','desc')->first() );
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
