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
    <link rel="manifest" href="/manifest.json">
    <link href="{{mix('/css/app.css')}}" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-default navbar-static-top" >
        <div class="container">
            <div class="navbar-header naos hidden-md hidden-lg" style="display:inline-block">
                    <!-- Branding Image  -->
                <a class="navbar-brand" style="font-size: inherit;color: inherit;" href="#/">
                    {{ config('app.name') }}
                </a>
            </div>
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="{{ route('login') }}">Login</a></li>
                    <li><a href="{{ route('register') }}">Register</a></li>
                </ul>
            </div>
        </div>
    </nav>
    @yield('content')
    <script src="{{mix('/js/auth.js')}}"></script>
    @include('layouts.footer')
</body>
</html>
