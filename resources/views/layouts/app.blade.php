<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" style="position: relative;min-height: 100%;">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="cornflowerblue">
    <meta name="robots" content="noindex, nofollow, noarchive"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>
    <link rel="manifest" href="manifest.json">
    <link href="/css/app.css" rel="stylesheet">
    <script>
        window.Laravel = {!! json_encode(\App\Http\Traits\Utility::data_fetch()) !!};
        window.Laravel.lng.currency = window.Laravel.lng[window.Laravel.currency.name];
        window.onload = function(){
            site_loading.style='display:none';
        };
    </script>
</head>
<body lang="{{ config('app.locale') }}">
    <div id='app'>
        <div id="site_loading" class="overlay-view">
            <i class="fa fa-cog fa-spin"></i>  
            <h4>@lang('app.loading')<span>.</span><span>.</span><span>.</span></h4>
        </div>
        <nav class="navbar navbar-default navbar-static-top" >
            <div class="container">
                <div class="navbar-header naos hidden-md hidden-lg" style="display:inline-block">
                     <!-- Branding Image  -->
                    <a class="navbar-brand" style="font-size: inherit;color: inherit;" href="#/">
                        <span>N<span class="vflip">V</span>OS</span>
                        <!-- {{ config('app.name') }} -->
                    </a>
                </div>
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <ul class="nav navbar-nav navbar-right" >
                        <li class="dropdown">
                            <a class="dropdown-toggle fake-link"
                                data-toggle="dropdown" aria-haspopup="true">
                                <i class="fa fa-globe"></i>
                                <img style="max-height:1.8em;margin-top:-0.5em" :src="`images/${lng.lang_name_ISO.toLowerCase()}.png`">
                                <span style="vertical-align:middle;text-transform: uppercase;">&nbsp;@{{lng.ISO_code}}&nbsp;</span><span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li v-for="(lng,ISO_code) in langs">
                                    <a class="fake-link" @click="get_locale(ISO_code)">
                                        <img :src="'images/'+ISO_code+'.png'"> &nbsp;&nbsp;@{{lng.text}}
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li v-if="!user"><a href="{{ route('login') }}">@{{lng.login}}</a></li>
                        <li v-if="!user"><a href="{{ route('register') }}">@{{lng.register}}</a></li>
                        <li v-if="user" class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" 
                                style="width: 16rem;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">
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
