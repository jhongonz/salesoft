@extends('.panel.base.layout_login')
@section('name_title','Control de Acceso')

@section('content')
<div class="login-box">
    <div class="login-logo">
        <a href="#"><b>Sale</b>soft</a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">Acceso al panel</p>

        <form method="post">
            <div id="message"></div>

            <div class="form-group has-feedback username">
                <input type="text" name="username" id="username" class="form-control" placeholder="Login">
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback password">
                <input type="password"  name="password" id="password" class="form-control" placeholder="Password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-8">
                </div>
                <div class="col-xs-4">
                    <button type="submit" id="btnLogin" class="btn btn-primary btn-sm btn-block btn-flat">Ingresar</button>
                </div>
            </div>
        </form>

        <!-- <a href="#">Recuperar acceso</a><br>
        <a href="#" class="text-center">Registro de nuevo usuario</a> -->

    </div>
</div>
@endsection

@section('javascript')
<script type="text/javascript">

$.ajaxSetup({
    headers : {
        'X-CSRF-TOKEN' : $('meta[name = csrf-token]').attr('content')
    }
});

$("#btnLogin").click(function(e){
    e.preventDefault();
    $.ajax({
        url : "{{ url('login') }}",
        data : {
            user_login : $("#username").val(),
            password : $("#password").val()
        },
        type : 'POST',
        success : function(response){
            
            if (response.status == STATUS_OK)
            {
                window.location.href = "{{ url('/home') }}";
            }
            else if (response.status == STATUS_FAIL)
            {
                $('.username').addClass('has-error');
                $('.password').addClass('has-error');
                $("#password").val('');
                
                toastr.error('Datos de acceso incorrecto.');
            }
        }
    });
});

$(document).ready(function(){
    $("#message").hide();
    $("#username").focus();
});
</script>
@endsection
