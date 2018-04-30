<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin QRBox</title>
    <link rel="stylesheet" href="{{asset('vendor/simple-line-icons/css/simple-line-icons.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/font-awesome/css/fontawesome-all.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/app-admin.css')}}">

    <link rel="stylesheet" href="{{asset('vendor/DataTables/datatables.min.css')}}">
    
</head>
<body class="sidebar-fixed header-fixed">
<div class="page-wrapper">
    
    @include('admin.header')

    <div class="main-container">
        
        @include('admin.sidebar')

        <div class="content">
            <div class="container-fluid">

                @yield('content')

            </div>
        </div>
    </div>
</div>
<script src="{{asset('vendor/jquery/jquery.min.js')}}"></script>
<script src="{{asset('vendor/popper.js/popper.min.js')}}"></script>
<script src="{{asset('vendor/bootstrap/js/bootstrap.min.js')}}"></script>
<script src="{{asset('vendor/chart.js/chart.min.js')}}"></script>
<script src="{{asset('vendor/DataTables/datatables.min.js')}}"></script>

<script src="{{asset('js/carbon.js')}}"></script>
<script src="{{asset('js/app-admin.js')}}"></script>

@yield('javascript')

</body>
</html>
