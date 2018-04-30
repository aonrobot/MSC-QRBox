<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <title>QR Box | Metrosystems</title>

        <link href="{{url(mix('css/login.css'))}}" rel="stylesheet" type="text/css">

<script type='text/javascript' src='{{asset('js/jquery.particleground.min.js')}}'></script>
    </head>
    <body>
        <div id="particles">
            <div id="intro">
                <h1>101 | <small>Not Have Permission To Access</small></h1>
            </div>
            <script>
            document.addEventListener('DOMContentLoaded', function () {
                particleground(document.getElementById('particles'), {
                    dotColor: '#DDD',
                    lineColor: '#EFEFEF'
                });
                var intro = document.getElementById('intro');
                intro.style.marginTop = - intro.offsetHeight / 2 + 'px';
            }, false);
        </script>
    </body>
</html>
