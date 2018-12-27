<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" style="position: relative;min-height: 100%;">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>

</head>
<body style="margin: 0 0 100px;padding-bottom:20px">
    <div id='app'>
            <router-view></router-view>
    </div>
    
        <footer style="position: absolute;
    bottom: 0;height:100px;width:100%;background-color:cornflowerblue;text-align:center">
            <div class="container">
                    <h3 style="color:white">Powered by DR 2017</h3>
                    <h5 style="color:white">formulaone.forever@gmail.com</h5>
            </div>
    </footer>
</body>
<script src="/js/dashboard.js"></script>
</html>
