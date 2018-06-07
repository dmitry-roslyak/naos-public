<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        if(isset($_COOKIE['lang']))
        {
            $lang = $_COOKIE['lang'];
            if($lang == 'RU' || $lang == 'ru' || $lang == 'ru-RU') { $lang = 'ru'; $currency = 'RUB'; }
            else if($lang == 'UA' || $lang == 'ua' || $lang == 'ua-UA') { $lang = 'ua'; $currency = 'UAH'; }
            else { $lang = 'en'; $currency = 'USD'; }
        }
        else{
            $temp = str_replace(";",',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
            $temp2 = explode(',',$temp);
            for ($index = 0; $index < count($temp2); $index++) {
                if($temp2[$index] == 'ru' || $temp2[$index] == 'ru-RU') { $lang = 'ru'; $currency = 'RUB'; }
                else if($temp2[$index] == 'ua') { $lang = 'ua'; $currency = 'UAH'; }
                else { $lang = 'en'; $currency = 'USD'; }
            }
        }
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'language' => $lang,
            'currency' => $currency
        ]);
    }
}
