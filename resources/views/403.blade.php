<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="basic-auth" content="{{ Crypt::encryptString($_SERVER['PHP_AUTH_USER']) }}">
        
        <title>QR Box | Metrosystems</title>
    </head>
    <body>
        <h2>Please Login First!!</h2>
    </body>
</html>
