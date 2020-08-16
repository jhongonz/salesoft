<div class="modal fade usuario_dialog" role="dialog">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Lista de Clientes</h4>
            </div>
            <div class="modal-body">
                <form role="form">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-xs-12">
                                <label for="customer">Cliente</label>
                                <select class="form-control select2" name="customer" id="customer" style="width: 100%">
                                    <option value="">Seleccione ...</option>
                                    @foreach($customers as $customer)
                                        <option value="{{$customer->cus_id}}">{{$customer->cus_name}} {{$customer->cus_lastname}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
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

    $.ajax({
        url: "{{ url('salepoint/save-customer') }}",
        data: {
            idCustomer : $('#customer').val()
        },
        type:'POST',
        success: function(response){

            if(response.status == STATUS_OK)
            {
                $('.usuario_dialog').modal('hide');

                window.location.reload();
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
});
</script>