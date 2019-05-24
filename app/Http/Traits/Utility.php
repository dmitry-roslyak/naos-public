<?php

namespace App\Http\Traits;

trait Utility
{
    public static function locale(Array $array = []) {
        if(!empty($array)) {}
        else if(!empty($_COOKIE['lang'])) $array[0] = $_COOKIE['lang'];
        else if(!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $array = explode(',', str_replace(";",',', $_SERVER['HTTP_ACCEPT_LANGUAGE']));
        } 
    
        $lang = 'en'; $currency = 'USD';
    
        for ($index = 0; $index < count($array); $index++) {
            if($array[$index] == 'ru' || $array[$index] == 'ru-RU') { $lang = 'ru'; $currency = 'RUB'; break; }
            else if($array[$index] == 'uk') { $lang = 'uk'; $currency = 'UAH'; break; }
        }
        return [
            'language' => $lang,
            'currency' => $currency
        ];
    }
    public static function data_fetch()
    {
        $user = \Auth::user();
        $locale = $user ? $user : Utility::locale();
        return [
            'langsAvailable' => \App\Lang::where('name', 'lang_name')->get(['text','lng'])->keyBy('lng'),
            'user' => $user,
            'catalog' => \App\Category::get()->keyBy('name'),
            'lng' => \App\Lang::where('lng', $locale['language'])->get(['name','text'])->mapWithKeys(function ($item) {
                return [$item['name'] => $item['text']];
            }), 
            'currency' => \App\Currency::where('name', $locale['currency'])->orderBy('date','desc')->first()
        ];
    }
}