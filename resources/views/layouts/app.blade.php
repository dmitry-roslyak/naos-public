<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" style="position: relative;min-height: 100%;">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="cornflowerblue">
    <meta name="robots" content="noindex, nofollow, noarchive"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="manifest" href="manifest.json">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="markup.js"></script>
    <script>
        window.Laravel = {!! 
            (function(){
                $user = Auth::user();
                if($user){
                    $currency = $user->currency;
                    $lang = $user->language;
                }
                else {
                    if(isset($_COOKIE['lang'])) $array[0] = $_COOKIE['lang'];
                    else $array = explode(',', str_replace(";",',', $_SERVER['HTTP_ACCEPT_LANGUAGE']));
                    for ($index = 0; $index < count($array); $index++) {
                        if($array[$index] == 'ru' || $array[$index] == 'ru-RU') { $lang = 'ru'; $currency = 'RUB'; break; }
                        else if($array[$index] == 'ua' || $array == 'ua-UA') { $lang = 'ua'; $currency = 'UAH'; break; }
                        else { $lang = 'en'; $currency = 'USD'; }
                    }
                }
                return json_encode([
                    'csrfToken' => csrf_token(),
                    'langsAvailable' => App\Lang::where('name', 'lang_name_ISO')->orWhere('name', 'lang_name')->
                orWhere('name', 'img_path')->orderBy('name','asc')->get(['text','lng','name'])->groupBy('lng'),
                    'user' =>  $user,
                    'catalog' => App\Category::get(),
                    'lng' => App\Lang::where('lng', $lang)->get(['name','text']), 
                    'currency' => App\Currency::where('name', $currency)->orderBy('date','desc')->first()
                ]);
            })();
        !!};
        window.lng = {};
        window.Laravel.lng.map(function (t) { window.lng[t.name] = t.text; });
        window.lng.currency = window.lng[window.Laravel.currency.name];
        window.onload = function(){
            site_loading.style='display:none';
        };
    </script>
</head>
<body>
    <div id='app'>
        <div :data-err="ajaxError?true:false" class="ajax-error thumbnail">
            <i class="fa fa-warning"></i>&nbsp;@{{ajaxError}}
            <span class="fake-link err-close" @click="ajaxError=0">X</span>
        </div>
        <div id="site_loading" class="overlay-view">
            <i class="fa fa-cog fa-spin" style="font-size:10rem"></i>  
            <h4>Loading</h4>
        </div>
        <nav class="navbar navbar-default navbar-static-top" >
            <div class="container">
                <!--<div class="navbar-header">
                     Branding Image 
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                </div>-->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <ul class="nav navbar-nav navbar-right" >
                        <li class="dropdown">
                            <a class="dropdown-toggle fake-link" style="text-decoration: none"
                                data-toggle="dropdown" aria-haspopup="true">
                                <i class="fa fa-globe"></i>
                                <img style="max-height:1.8em;margin-top:-0.5em" :src="lng.img_path">
                                <span style="vertical-align:middle;">&nbsp;@{{lng.lang_name_ISO}}&nbsp;</span><span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li v-for="lng in langs">
                                    <a class="fake-link" @click="get_locale(lng.ISO)">
                                        <img :src="lng.img"> &nbsp;&nbsp;@{{lng.name}}
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li v-if="!user"><a href="{{ route('login') }}">@{{lng.login}}</a></li>
                        <li v-if="!user"><a href="{{ route('register') }}">@{{lng.register}}</a></li>
                        <li v-if="user" class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                @{{user}} <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <router-link to="/account">
                                        <i class="fa fa-user"></i>&nbsp;&nbsp;@{{lng.myprofile}}
                                    </router-link>
                                </li>
                                <li>
                                    <a @click="logout()">
                                        <i class="fa fa-sign-out"></i>&nbsp;&nbsp;@{{lng.logout}}
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        @yield('content')
    </div>
</body>
<script src="/js/app.js" async defer></script>
</html>
