<div class="modal fade usuario_dialog" role="dialog">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Registro de Usuario</h4>
            </div>
            <div class="modal-body">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li id="tag-generales"><a href="#generales" data-toggle="tab">Datos Personales</a></li>
                        <li id="tag-sistema"><a href="#sistema" data-toggle="tab">Cuenta Usuario</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane" id="generales">
                            <form role ="form">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <label for="tipoDoc">Tipo Documento</label>
                                            <select class="form-control select2" name="tipoDoc" id="tipoDoc" style="width: 100%">
                                                <option value="">Seleccione ...</option>
                                                @foreach(TYPE_DOCUMENT as $index => $documento)
                                                    <option value="{{$index}}" @isset($manager) @if($manager->admin_identifier_type == $index) selected @endif @endisset>{{$documento}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-xs-6 identificacion">
                                            <label for="identificacion">Nro  de identificación</label>
                                            <input type="text" class="form-control" name="identificacion" id="identificacion" value="@isset($manager){{$manager->admin_identifier}}@endisset">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-6 nombres">
                                            <label for="nombre_usuario">Nombres</label>
                                            <input type="text" class="form-control" name="nombre_usuario" id="nombre_usuario" value="@isset($manager){{$manager->admin_name}}@endisset">
                                        </div>
                                        <div class="col-xs-6 apellidos">
                                            <label for="apellido_usuario">Apellidos</label>
                                            <input type="text" class="form-control" name="apellido_usuario" id="apellido_usuario" value="@isset($manager){{$manager->admin_lastname}}@endisset">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-6 telefono">
                                            <label for="telefono">Telefono</label>
                                            <input type="text" class="form-control" name="telefono" id="telefono" value="@isset($manager){{$manager->admin_phone}}@endisset">
                                        </div>
                                        <div class="col-xs-6 email">
                                            <label for="email">Email</label>
                                            <input type="text" class="form-control" name="email" id="email" value="@isset($manager){{ $manager->admin_email}}@endisset">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 direccion">
                                            <label for="direccion">Dirección</label>
                                            <textarea class="form-control" name="direccion" id="direccion">@isset($manager){{$manager->admin_address}}@endisset</textarea>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane" id="sistema">
                            <form role="form">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xs-6 login">
                                            <label for="login">Login</label>
                                            <input type="text" class="form-control" name="login" id="login" value="@isset($manager){{$manager->user_login}}@endisset">
                                        </div>
                                        @if(!$manager)
                                        <div class="col-xs-6 password">
                                            <label for="password">Clave</label>
                                            <input type="password" class="form-control" name="password" id="password">
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancelar btn btn-default btn-sm" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn-aceptar btn btn-primary btn-sm">Guardar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<script type="text/javascript">

$(".btn-cancelar").click(function(e){
    e.preventDefault();
    $('.usuario_dialog').modal('hide');
});

$(".btn-aceptar").click(function(e){
    e.preventDefault();

    var datos = {
        nombres : $("#nombre_usuario").val(),
        apellidos : $("#apellido_usuario").val(),
        identificacion : $("#identificacion").val(),
        tipoDocumento : $("#tipoDoc").val(),
        direccion : $("#direccion").val(),
        telefono : $("#telefono").val(),
        email : $("#email").val(),
        login : $("#login").val(),
        password : $("#password").val(),
        idUsuario : "{{$idAdmin}}",
    };

    $.ajax({
        url: "{{ url('manager/save') }}",
        data: datos,
        type:'POST',
        success: function(response){

            if(response.status == STATUS_OK)
            {
                $('.usuario_dialog').modal('hide');

                $('#content_data').DataTable().ajax.reload();
                toastr.success('Registro actualizado.');
            }
            else
            {
                var errors = response.errors;
                toastr.error('Error de entrada.');

                $.each(errors, function(indice, elemento) {
                    $('.' + indice).addClass('has-error');
                });
            }
        }
    });
});

$(document).ready(function()
{   
    $('.select2').select2();

    $('.usuario_dialog').modal('show');
    $(".modal-body").css("overflow-y","auto");

    $('#generales').addClass('active');
    $('#tag-generales').addClass('active');
});
</script>