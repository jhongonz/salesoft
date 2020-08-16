<div class="modal fade usuario_dialog" role="dialog">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Registro de Cliente</h4>
            </div>
            <div class="modal-body">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li id="tag-generales"><a href="#generales" data-toggle="tab">Datos Personales</a></li>
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
                                                    <option value="{{$index}}" @isset($customer) @if($customer->cus_document_type == $index) selected @endif @endisset>{{$documento}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-xs-6 identificacion">
                                            <label for="identificacion">Nro  de identificación</label>
                                            <input type="text" class="form-control" name="identificacion" id="identificacion" value="@isset($customer){{$customer->cus_document_number}}@endisset">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-6 nombres">
                                            <label for="nombre_usuario">Nombres</label>
                                            <input type="text" class="form-control" name="nombre_usuario" id="nombre_usuario" value="@isset($customer){{$customer->cus_name}}@endisset">
                                        </div>
                                        <div class="col-xs-6 apellidos">
                                            <label for="apellido_usuario">Apellidos</label>
                                            <input type="text" class="form-control" name="apellido_usuario" id="apellido_usuario" value="@isset($customer){{$customer->cus_lastname}}@endisset">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-6 telefono">
                                            <label for="telefono">Telefono</label>
                                            <input type="text" class="form-control" name="telefono" id="telefono" value="@isset($customer){{$customer->cus_phone}}@endisset">
                                        </div>
                                        <div class="col-xs-6 email">
                                            <label for="email">Email</label>
                                            <input type="text" class="form-control" name="email" id="email" value="@isset($customer){{$customer->cus_email}}@endisset">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <label for="gender">Genero</label>
                                            <select class="form-control select2" name="gender" id="gender" style="width: 100%">
                                                <option value="">Seleccione ...</option>
                                                @foreach(TYPE_GENDER as $index => $gender)
                                                    <option value="{{$index}}" @isset($customer) @if($customer->cus_gender == $index) selected @endif @endisset>{{$gender}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-xs-6">
                                            <label for="birthdate">Fecha de Nacimiento</label>
                                            <input type="text" class="form-control pull-right" name="birthdate" id="birthdate" value="@isset($customer){{$customer->cus_birthdate}}@endisset">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 direccion">
                                            <label for="direccion">Dirección</label>
                                            <textarea class="form-control" name="direccion" id="direccion">@isset($customer){{$customer->cus_address}}@endisset</textarea>
                                        </div>
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

$('#birthdate').datepicker({
    autoclose: true,
    language : 'es'
});

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
        gender : $("#gender").val(),
        birthdate : $("#birthdate").val(),
        idCustomer : "{{$idCustomer}}",
    };

    $.ajax({
        url: "{{ url('customer/save') }}",
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