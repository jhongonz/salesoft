@extends($layout)

@section('content-body')
<section class="content-header">

    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li>Base</li>
        <li class="breadcrumb-item active">Pedidos</li>
    </ol><br>
</section>

<section class="content">
<div class="row">
    <div class="col-12">
        <div class="box">
            <div class="box-body">
                <table id="content_data" class="table table-bordered table-striped display pageResize">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Nro</th>
                            <th>Cliente</th>
                            <th>Subtotal</th>
                            <th>Total</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->
</section>
@stop

@section('javascript')
@parent
<script type="text/javascript">

$('#content_data').DataTable({
    language: {
        url: "{{ URL::asset('theme/bower_components/datatables.net-bs/Spanish.json') }}"
    },
    dom: DOM_TABLE,
    buttons: [
        {
            text: '<i class="fa fa-refresh" style="color:grey;"></i>',
            className: 'btn btn-sm',
            action: function (e, dt, node, config) {
                e.preventDefault();

                $('#content_data').DataTable().ajax.reload();
            }
        }
    ],
    lengthChange: false,
    processing: true,
    serverSide: true,
    autoWidth: false,
    scrollCollapse : false,
    scrollY : '45vh',
    pageLength: 30,
    pageResize : true,
    ajax : {
        url : "{{ url('sale/get-main-list') }}",
        type : 'POST',
        data : null
    },
    order : [
        [2,'asc']
    ],
    columns: [
        { data: 'tool', name: 'tool',orderable: false, searchable: false, width: 15},
        { data: 'number', name: 'number', width: 100},
        { data: 'name', name: 'name'},
        { data: 'subtotal', name: 'subtotal', width: 100},
        { data: 'total', name: 'total', width: 100},
        { data: 'state', name: 'state',orderable: false, searchable: false, width: 100}
    ],
    drawCallback: function() 
    {
        $(".ajxDeleteSale").click(function(e){
            e.preventDefault();
            var _idSale = $(this).data('idsale');

            swal({
                //title: "Eliminar",
                text: "Esta seguro de realizar esta operación.?",
                icon: "warning",
                buttons: ['Cancelar','Aceptar'],
                dangerMode: true,
            })
            .then((value) =>
            {
                if (value)
                {
                    $.ajax({
                        url : "{{ url('sale/delete') }}",
                        type : 'POST',
                        data : {idSale : _idSale},
                        success : function(response)
                        {
                            if (response.status == STATUS_OK)
                            {
                                $('#content_data').DataTable().ajax.reload();
                                toastr.success('Registro eliminado con exito.');
                            }
                            else if (response.status == STATUS_FAIL)
                            {
                                toastr.error('No se pudo eliminar el registro.');
                            }
                        }
                    });
                }
            });                
        });

        $(".ajxChangeStatus").click(function(e){
            e.preventDefault();
            var _idSale = $(this).data('idsale');

            $.ajax({
                url : "{{ url('sale/change-state') }}",
                type : 'POST',
                data : {idSale : _idSale},
                success : function(response){

                    if (response.status == STATUS_OK)
                    {
                        $('#content_data').DataTable().ajax.reload();
                        toastr.success(response.msg);
                    }
                    else
                    {
                        toastr.error('Error en operación.');
                    }
                }
            });
        });
    }
});

$(document).ready(function(){

});

</script>
@endsection