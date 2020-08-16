<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('name_title','Log in') | Salesoft</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ URL::asset('theme/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ URL::asset('theme/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ URL::asset('theme/bower_components/Ionicons/css/ionicons.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ URL::asset('theme/dist/css/AdminLTE.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ URL::asset('theme/plugins/iCheck/square/blue.css') }}">
    <!-- Modal alert -->
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/toastr.min.css') }}">

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    @yield('styles')
</head>
<body class="hold-transition login-page skin-green">
    @yield('content')

    <!-- jQuery 3 -->
    <script src="{{ URL::asset('theme/bower_components/jquery/dist/jquery.min.js') }}"></script>
    <!-- Bootstrap 3.3.7 -->    
    <script src="{{ URL::asset('theme/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <!-- iCheck -->
    <script src="{{ URL::asset('theme/plugins/iCheck/icheck.min.js') }}"></script>
    <script src="{{ URL::asset('js/toastr.min.js') }}"></script>

    <!-- application -->
    <script src="{{ URL::asset('js/app.js') }}" charset="utf-8"></script>

    <script>
        $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' /* optional */
            });
        });
    </script>
    @yield('javascript')
</body>
</html>