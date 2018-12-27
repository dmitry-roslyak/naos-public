<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Currency;
use App\Lang;
use Cookie;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('vue');
    }
    public function currency(Request $data)
    {
        $user = Auth::user();
        if($user) {
            if($data->val=='RUB') $user->currency = 'RUB';
            elseif ($data->val=='UAH') $user->currency = 'UAH';
            else $user->currency = 'USD';
            $user->save();
            return Currency::where('name', $user->currency)->orderBy('date','desc')->first();
        }
    }
    public function lang(Request $data)
    {
        $user = Auth::user();
        if($user) {
            if($data->lng=='RU'||$data->lng=='ru'||$data->lng=='ru-RU') $user->language = 'ru';
            elseif ($data->lng=='UA'||$data->lng=='ua'||$data->lng=='ua-UA') $user->language = 'ua';
            else $user->language = 'en';
            $user->save();
            return [Lang::where('lng', $user->language)->get(['name','text']),
                Currency::where('name', $user->currency)->orderBy('date','desc')->first()];
        }
        else {
            if($data->lng=='RU'||$data->lng=='ru'||$data->lng=='ru-RU') { $lang = 'ru'; $currency = 'RUB'; }
            elseif ($data->lng=='UA'||$data->lng=='ua'||$data->lng=='ua-UA'){ $lang = 'ua'; $currency = 'UAH'; }
            else{ $lang = 'en'; $currency = 'USD'; }
            Cookie::queue('lang', $lang, 86400 * 60);
            return [Lang::where('lng', $lang)->get(['name','text']), Currency::where('name', $currency )->orderBy('date','desc')->first()];
        }
    }
}
