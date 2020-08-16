@extends($layout)

@section('content-body')
<section class="content-header">
    <button class="new-manager btn btn-primary btn-sm">Nuevo Administrador &nbsp;&nbsp;&nbsp;<i class="fa fa-plus"></i></button>

    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li>Configuraciones</li>
        <li class="breadcrumb-item active">Administradores</li>
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
                            <th>Login</th>
                            <th>Nombres</th>
                            <th>Apellidos</th>
                            <th>Email</th>
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
        url : "{{ url('manager/get-main-list') }}",
        type : 'POST',
        data : null
    },
    order : [
        [2,'asc']
    ],
    columns: [
        { data: 'tool', name: 'tool',orderable: false, searchable: false, width: 15},
        { data: 'user_id', name: 'user_id', width: 20, orderable : false, searchable : false},
        { data: 'user_login', name: 'user_login'},
        { data: 'admin_name', name: 'admin_name'},
        { data: 'admin_lastname', name: 'admin_lastname'},
        { data: 'admin_email', name: 'admin_email'},
        { data: 'state', name: 'state',orderable: false, searchable: false, width: 100}
    ],
    drawCallback: function() 
    {
        $(".ajxEditUser").click(function(e){
            e.preventDefault();
            var _idAdmin = $(this).data('idadmin');

            $.ajax({
                url : "{{ url('manager/get-registry') }}",
                type : 'POST',
                data : {idAdmin : _idAdmin},
                success : function(response) {
                    $("#content-model").html(response.html);
                }
            });
        });

        $(".ajxDeleteUser").click(function(e){
            e.preventDefault();
            var _idAdmin = $(this).data('idadmin');

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
                        url : "{{ url('manager/delete') }}",
                        type : 'POST',
                        data : {idAdmin : _idAdmin},
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
            var _idAdmin = $(this).data('idadmin');

            $.ajax({
                url : "{{ url('manager/change-state') }}",
                type : 'POST',
                data : {idAdmin : _idAdmin},
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

$(".new-manager").click(function(e){
    e.preventDefault();

    $.ajax({
        url : "{{ url('manager/get-registry') }}",
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