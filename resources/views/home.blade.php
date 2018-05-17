<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="app-url" content="{{ str_replace('//', '\\\\', env('APP_URL')) }}">        
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="basic-auth" content="{{ Crypt::encryptString(Auth::user()->getAccountName())}}">
        
        <title>QR Box | Metrosystems</title>

        <link href="{{url(mix('css/app.css'))}}" rel="stylesheet" type="text/css">

        
    </head>
    <body>
        <div id="main"></div>        
        <script src="{{url('js/filepond-polyfill.min.js')}}"></script>        
        <script src="{{url(mix('js/app.js'))}}" ></script>
    </body>
</html>
