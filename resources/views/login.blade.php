
<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <title>QR Box Login | Metrosystems</title>
        <link href="{{url(mix('css/login.css'))}}" rel="stylesheet" type="text/css">

        <script type='text/javascript' src='{{asset('vendor/jquery/jquery.min.js')}}'></script>        
        <script type='text/javascript' src='{{asset('js/jquery.particleground.min.js')}}'></script>
        <script type='text/javascript' src='{{asset('vendor/jquery-browser-plugin/jquery.browser.min.js')}}'></script>

        <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
        
    </head>
    <body>
        <div id="particles">
            <div id="intro">
                <img src="{{asset('images/logo.png')}}" alt="" width="290" height="100">
                <form action="{{asset('/do.login')}}" method="POST" id="formLogin" class="form-signin">
                    {{ csrf_field() }}
                    <div class="text-center mb-4">
                        
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

                <div class="card cardIE m-auto w-75" style="display:none;">
                    <div class="card-header">
                        Internet Explorer <span id="bVersion"></span> <span class="badge badge-warning ml-2">Warning</span>
                    </div>
                    <div class="card-body">
                        <a type="button" class="btn btn-primary btn-lg btn-block mb-5" id="openEdge" href="microsoft-edge:https://fora.metrosystems.co.th/qrbox"><i data-feather="send"></i> เปิดใช้งานด้วย Microsoft Edge</a>
                        <div class="row">
                            <div class="col-md-6">
                                <img src="{{asset('images/sad.gif')}}" class="mb-4">
                                <blockquote class="blockquote mb-0">
                                <h3>" อย่าใช้ <b>Internet Explorer</b> กันเลยน้าาา "</h3>
                                <footer class="blockquote-footer"><cite title="Source Title">Friends don't let friends use internet explorer</cite></footer>
                                </blockquote>
                            </div>
                            <div class="col-md-3">
                                <div class="card" style="width: 18rem;">
                                <img src="{{asset('images/edge.png')}}" class="mb-4">
                                <div class="card-body">
                                    <blockquote class="blockquote mb-0">
                                    <h5>" Microsoft แนะนำให้ใช้ <b>Microsoft Edge</b> เป็น Browser สำหรับใช้งาน Internet "</h5>
                                    <footer class="blockquote-footer"><cite title="Source Title">Microsoft</cite></footer>
                                    </blockquote>
                                </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card" style="width: 18rem;">
                                <img src="{{asset('images/google.png')}}" class="mb-4">
                                <div class="card-body">
                                    <blockquote class="blockquote mb-0">
                                    <h5>" <b>Google Chrome</b> เป็น Browser ที่ดีที่สุด "</h5>
                                    <footer class="blockquote-footer"><cite title="Source Title">QRBox Team</cite></footer>
                                    </blockquote>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <script>
            feather.replace()

            if($.browser.msie){
                $('#formLogin').empty();
                $('.cardIE').show();
                $('#bVersion').html($.browser.version)

            }
                       
        </script>

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
