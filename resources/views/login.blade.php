
<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <title>QR Box Login | Metrosystems</title>
        <link href="{{url(mix('css/login.css'))}}" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.12/css/all.css" integrity="sha384-G0fIWCsCzJIMAVNQPfjH08cyYaUtMwjJwqiRKxxE/rx96Uroj1BtIQ6MLJuheaO9" crossorigin="anonymous">

        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>    
        <script type='text/javascript' src='{{asset('js/jquery.particleground.min.js')}}'></script>
        <script type='text/javascript' src='{{asset('vendor/jquery-browser-plugin/jquery.browser.min.js')}}'></script>

        
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
                        <input type="text" id="inputEmail" name="username" class="form-control mb-3" placeholder="Username (ex. somsakit)" required="" autofocus="">
                    </div>
                    
                    <div class="form-label-group text-left">
                        <input type="password" id="inputPassword" name="password" class="form-control mb-3" placeholder="Password" required="">
                    </div>

                    <button class="btn btn-outline-secondary btn-block" type="button" data-toggle="modal" data-target="#registerSoonModal"><i class="fas fa-registered mr-1"></i> มาสมัครใช้งานกัน</button>

                    <button class="btn btn-lg btn-primary btn-block" type="submit"><i class="fas fa-sign-in-alt mr-1"></i> <small>Login</small> เข้าใช้งาน</button>

                </form>

                <div class="card cardIE m-auto w-75" style="display:none;">
                    <div class="card-header">
                        Internet Explorer <span id="bVersion"></span> <span class="badge badge-warning ml-2">Warning</span>
                    </div>
                    <div class="card-body">
                        <h1><i class="fas fa-exclamation-circle mr-1"></i> QRBox ยังไม่สามารถใช้งานผ่าน Internet Explorer ได้ในขณะนี้</h1>
                        <hr/>
                        <a type="button" class="btn btn-primary btn-lg btn-block mb-5 mt-5" id="openEdge" href="microsoft-edge:https://fora.metrosystems.co.th/qrbox"><i class="fas fa-external-link-alt"></i> เปิดใช้งานด้วย Microsoft Edge แทน</a>
                        <hr/><br><br>

                        <h4 class="text-left mb-3"><i class="far fa-question-circle mr-1"></i> ทำไมถึงไม่แนะนำให้ใช้งาน Web Application ผ่าน Internet Explorer</h4>
                        <p class="text-left">
                            <button class="btn btn-outline-success btn-lg" type="button" data-toggle="collapse" data-target="#whyNotUseIE" aria-expanded="false" aria-controls="whyNotUseIE">
                                คำตอบ
                            </button>
                        </p>
                        <div class="collapse" id="whyNotUseIE">
                            <div class="card card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <img src="{{asset('images/sad.gif')}}" class="mb-4">
                                        <blockquote class="blockquote mb-0">
                                        <h3>" อย่าใช้ <b>Internet Explorer</b> กันเลยนะ "</h3>
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
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="registerSoonModal" tabindex="-1" role="dialog" aria-labelledby="registerSoonModalTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registerSoonModalTitle"><i class="fas fa-exclamation-triangle"></i> ขออภัยในความไม่สะดวก</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5>ระบบสมัครสามชิก จะเปิดให้ใช้บริการเร็วๆนี้</h5>
                    <h5><small>กรุณาติดต่อ MIS เพื่อสมัครใช้งานก่อนได้ค่ะ</small></h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times-circle"></i> Close</button>
                </div>
                </div>
            </div>
        </div>

        <script>
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
