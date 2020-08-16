@extends($layout)

@section('content-body')
<section class="content-header">
    <button class="new-product btn btn-primary btn-sm">Nuevo Producto &nbsp;&nbsp;&nbsp;<i class="fa fa-plus"></i></button>

    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li>Base</li>
        <li class="breadcrumb-item active">Productos</li>
    </ol>
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
                            <th>Código</th>
                            <th>Producto</th>
                            <th>Categoria</th>
                            <th>Precio</th>
                            <th>Precio Oferta</th>
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
        url : "{{ url('product/get-main-list') }}",
        type : 'POST',
        data : null
    },
    order : [
        [2,'asc']
    ],
    columns: [
        { data: 'tool', name: 'tool',orderable: false, searchable: false, width: 15},
        { data: 'pro_code', name: 'pro_code', width: 20, orderable : false, searchable : false},
        { data: 'pro_name', name: 'pro_name'},
        { data: 'cate_name', name: 'cate_name'},
        { data: 'price', name: 'price', width: 100},
        { data: 'priceCut', name: 'priceCut', width: 100},
        { data: 'state', name: 'state',orderable: false, searchable: false, width: 100}
    ],
    drawCallback: function() 
    {
        $(".ajxEditProduct").click(function(e){
            e.preventDefault();
            var _idProduct = $(this).data('idproduct');

            $.ajax({
                url : "{{ url('product/get-registry') }}",
                type : 'POST',
                data : {idProduct : _idProduct},
                success : function(response) {
                    $("#content-model").html(response.html);
                }
            });
        });

        $(".ajxDeleteProduct").click(function(e){
            e.preventDefault();
            var _idProduct = $(this).data('idproduct');

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
                        url : "{{ url('product/delete') }}",
                        type : 'POST',
                        data : {idProduct : _idProduct},
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
            var _idProduct = $(this).data('idproduct');

            $.ajax({
                url : "{{ url('product/change-state') }}",
                type : 'POST',
                data : {idProduct : _idProduct},
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

$(".new-product").click(function(e){
    e.preventDefault();

    $.ajax({
        url : "{{ url('product/get-registry') }}",
        type : 'POST',
        data : null,
        success : function(response) {
            $("#content-model").html(response.html);
        }
    });
});

$(document).ready(function(){

});

</script>
@endsection