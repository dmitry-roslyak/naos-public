<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
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
  <nav class="navbar navbar-inverse">
    <div class="container">
      <div class="navbar-header">
        <a href="/" class="navbar-brand">{{ config('app.name') }}</a>
      </div>
      <ul class="nav navbar-nav navbar-right">
          <li><a href="{{ route('login') }}">Login</a></li>
          <li><a href="{{ route('register') }}">Register</a></li>
      </ul>
    </div>
  </nav>
  @yield('content')
  <script src="{{mix('/js/auth.js')}}"></script>
  @include('layouts.footer')
</body>
</html>
