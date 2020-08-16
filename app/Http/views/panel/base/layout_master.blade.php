<!DOCTYPE html>
<html class="html" lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Salesoft</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ URL::asset('theme/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ URL::asset('theme/bower_components/select2/dist/css/select2.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ URL::asset('theme/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ URL::asset('theme/bower_components/Ionicons/css/ionicons.min.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ URL::asset('theme/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="{{ URL::asset('theme/plugins/iCheck/all.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ URL::asset('theme/dist/css/AdminLTE.min.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ URL::asset('theme/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('theme/bower_components/datatables.net-bs/css/buttons.bootstrap.min.css') }}">
    <!-- Modal alert -->
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/toastr.min.css') }}">

    <!-- AdminLTE Skins. Choose a skin from the css/skins
    folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{ URL::asset('theme/dist/css/skins/_all-skins.min.css') }}">
    <!-- Pace style -->
    <link rel="stylesheet" href="{{ URL::asset('theme/plugins/pace/pace.min.css') }}">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    @yield('styles')
</head>
<body class="hold-transition skin-black fixed sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">
    
    <header class="main-header">
        <!-- Logo -->
        <a href="{{ url('home') }}" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>Sale</b></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>Sale</b>soft</span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    @yield('menu_top')
                    <!-- User Account: style can be found in dropdown.less -->
                    @include('panel.menu.menu_user')
                    <!-- Control Sidebar Toggle Button -->
                    <!-- <li>
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                    </li> -->
                </ul>
            </div>
        </nav>
    </header>

    <!-- =============================================== -->

    <!-- Left side column. contains the sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- sidebar menu: : style can be found in sidebar.less -->
            @include('panel.menu.menu_principal')
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- =============================================== -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" id="content-body">
        @yield('content-body')
        <!-- <section class="content-header">
            @yield('name_page')

            @yield('navigation')
        </section>

        <section class="content">
            @yield('content-main')
        </section> -->
    </div>
    <!-- /.content-wrapper -->
</div>
<!-- ./wrapper -->

<!-- MODALS Y VENTANAS EMERGENTES -->
<div id="content-model"></div>

<!-- jQuery 3 -->
<script src="{{ URL::asset('theme/bower_components/jquery/dist/jquery.min.js') }}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ URL::asset('theme/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- Select2 -->
<script src="{{ URL::asset('theme/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
<!-- DataTables -->
<script src="{{ URL::asset('theme/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('theme/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ URL::asset('theme/bower_components/datatables.net-bs/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ URL::asset('theme/bower_components/datatables.net-bs/js/buttons.bootstrap.min.js') }}"></script>
<!-- <script src="{{ URL::asset('theme/bower_components/datatables.net-bs/js/dataTables.pageResize.min.js') }}"></script> -->
<!-- PACE -->
<script src="{{ URL::asset('theme/bower_components/PACE/pace.min.js') }}"></script>
<!-- SlimScroll -->
<script src="{{ URL::asset('theme/bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
<!-- FastClick -->
<script src="{{ URL::asset('theme/bower_components/fastclick/lib/fastclick.js') }}"></script>
<!-- iCheck 1.0.1 -->
<script src="{{ URL::asset('theme/plugins/iCheck/icheck.min.js') }}"></script>
<!-- bootstrap datepicker -->
<script src="{{ URL::asset('theme/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ URL::asset('theme/bower_components/bootstrap-datepicker/js/locales/bootstrap-datepicker.es.js') }}" charset="UTF-8"></script>

<!-- AdminLTE App -->
<script src="{{ URL::asset('theme/dist/js/adminlte.min.js') }}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ URL::asset('theme/dist/js/demo.js') }}"></script>

<!-- application -->
<script src="{{ URL::asset('js/app.js') }}" charset="utf-8"></script>
<!-- MODAL -->
<script src="{{ URL::asset('js/sweetalert.min.js') }}"></script>
<script src="{{ URL::asset('js/toastr.min.js') }}"></script>
<script type="text/javascript">

$(document).ready(function () {
    $('.sidebar-menu').tree();

    $(document).ajaxStart(function () {
        Pace.restart();
    });

    $('.action-menu').click(function(e){
        e.preventDefault();
        var _url = $(this).data('url');

        $.ajax({
            url: _url,
            type : 'GET',
            cache:false,
            success: function (response){
                
                $("#content-body").html(response);
                window.history.pushState("data","Title",_url);
            }
        });
    });
});

$.ajaxSetup({
    headers : {
        'X-CSRF-TOKEN' : $('meta[name = csrf-token]').attr('content')
    }
});

$('.logout').click(function(e){
    e.preventDefault();
    $.ajax({
        url : "{{ url('logout') }}",
        type : 'POST',
        data : null,
        success : function (response){
            if (response.status == STATUS_OK)
            {
                window.location.href = "{{ config('baseroot.panel') }}";
            }
        }
    });
});
</script>
@yield('javascript')
</body>
</html>
