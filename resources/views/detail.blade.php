<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" style="position: relative;min-height: 100%;">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="cornflowerblue">
    <meta name="robots" content="nofollow, noarchive"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>
    <link rel="manifest" href="/manifest.json">
    {!! \App\Http\Traits\Utility::markup($id) !!}
</head>
<body style="margin: 0;height: fit-content;overflow: hidden;">
    <iframe src="/#/detail/{{$id}}" frameborder="0" style="min-height:100%;width:100%"></iframe>
</body>
</html>
