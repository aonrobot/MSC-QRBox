
<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <title>QR Box Login | Metrosystems</title>
        <link href="{{url(mix('css/login.css'))}}" rel="stylesheet" type="text/css">

        <script type='text/javascript' src='{{asset('js/jquery.particleground.min.js')}}'></script>
    </head>
    <body>
        <div id="particles">
            <div id="intro">
                <form action="{{asset('/do.login')}}" method="POST" class="form-signin">
                    {{ csrf_field() }}
                    <div class="text-center mb-4">
                        <img class="mb-4" src="{{asset('images/logo.png')}}" alt="" width="290" height="100">
                        <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
                        @if(session('message'))
                        <div class="alert alert-danger" role="alert">
                            <p> {{ session('message') }} </p>
                        </div>
                        @endif
                    </div>

                    <div class="form-label-group text-left">
                        <input type="text" id="inputEmail" name="username" class="form-control mb-3" placeholder="Email address" required="" autofocus="">
                    </div>
                    
                    <div class="form-label-group text-left">
                        <input type="password" id="inputPassword" name="password" class="form-control mb-3" placeholder="Password" required="">
                    </div>

                    <button class="btn btn-outline-secondary btn-block" type="button" onclick="">สมัครสมาชิก</button>

                    <button class="btn btn-lg btn-primary btn-block" type="submit">ลงชื่อเข้าใช้งาน</button>

                </form>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                particleground(document.getElementById('particles'), {
                    //for present
                    // dotColor: '#CECECE',
                    // lineColor: '#E5E5E5'
                    dotColor: '#E5E5E5',
                    lineColor: '#F9F9F9'
                });
                var intro = document.getElementById('intro');
                intro.style.marginTop = - intro.offsetHeight / 2 + 'px';
            }, false);
        </script>
    </body>
</html>
