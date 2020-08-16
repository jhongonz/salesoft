<div class="modal fade usuario_dialog" role="dialog">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Registro de Categoria</h4>
            </div>
            <div class="modal-body">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li id="tag-generales"><a href="#generales" data-toggle="tab">Datos de Categoria</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane" id="generales">
                            <form role ="form">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xs-12 name">
                                            <label for="name">Nombre de Categoria</label>
                                            <input type="text" class="form-control" name="name" id="name" value="@isset($category){{$category->cate_name}}@endisset">
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
        name : $("#name").val(),
        idCategory : "{{$idCategory}}",
    };

    $.ajax({
        url: "{{ url('category/save') }}",
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