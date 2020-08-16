@extends($layout)

@section('content-body')
<section class="content-header">
    <button class="new-category btn btn-primary btn-sm">Nueva Categoria &nbsp;&nbsp;&nbsp;<i class="fa fa-plus"></i></button>

    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li>Base</li>
        <li class="breadcrumb-item active">Categorias</li>
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
                            <th>ID</th>
                            <th>Categoria</th>
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
        url : "{{ url('category/get-main-list') }}",
        type : 'POST',
        data : null
    },
    order : [
        [2,'asc']
    ],
    columns: [
        { data: 'tool', name: 'tool',orderable: false, searchable: false, width: 15},
        { data: 'cate_id', name: 'cate_id', width: 20, orderable : false, searchable : false},
        { data: 'cate_name', name: 'cate_name'},
        { data: 'state', name: 'state',orderable: false, searchable: false, width: 100}
    ],
    drawCallback: function() 
    {
        $(".ajxEditCategory").click(function(e){
            e.preventDefault();
            var _idCategory = $(this).data('idcategory');

            $.ajax({
                url : "{{ url('category/get-registry') }}",
                type : 'POST',
                data : {idCategory : _idCategory},
                success : function(response) {
                    $("#content-model").html(response.html);
                }
            });
        });

        $(".ajxDeleteCategory").click(function(e){
            e.preventDefault();
            var _idCategory = $(this).data('idcategory');

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
                        url : "{{ url('category/delete') }}",
                        type : 'POST',
                        data : {idCategory : _idCategory},
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
            var _idCategory = $(this).data('idcategory');

            $.ajax({
                url : "{{ url('category/change-state') }}",
                type : 'POST',
                data : {idCategory : _idCategory},
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

$(".new-category").click(function(e){
    e.preventDefault();

    $.ajax({
        url : "{{ url('category/get-registry') }}",
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