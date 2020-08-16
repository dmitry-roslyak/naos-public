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
    <script>window.Laravel = {!! json_encode(\App\Http\Traits\Utility::data_fetch()) !!};</script>
</head>
<body>
    @yield('content')
    <script src="{{mix('/js/app.js')}}"></script>
    @include('layouts.footer')
</body>
</html>
