@extends($layout)

@section('content-body')
<section class="content-header">
    <button class="new-customer btn btn-primary btn-sm">Nuevo Cliente &nbsp;&nbsp;&nbsp;<i class="fa fa-plus"></i></button>

    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li>Base</li>
        <li class="breadcrumb-item active">Clientes</li>
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
                            <th>Documento</th>
                            <th>Identificación</th>
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
        url : "{{ url('customer/get-main-list') }}",
        type : 'POST',
        data : null
    },
    order : [
        [2,'asc']
    ],
    columns: [
        { data: 'tool', name: 'tool',orderable: false, searchable: false, width: 15},
        { data: 'cus_id', name: 'cus_id', width: 20, orderable : false, searchable : false},
        { data: 'typeDocument', name: 'typeDocument'},
        { data: 'cus_document_number', name: 'cus_document_number'},
        { data: 'cus_name', name: 'cus_name'},
        { data: 'cus_lastname', name: 'cus_lastname'},
        { data: 'cus_email', name: 'cus_email'},
        { data: 'state', name: 'state',orderable: false, searchable: false, width: 100}
    ],
    drawCallback: function() 
    {
        //$('.ui.dropdown').dropdown();

        $(".ajxEditCustomer").click(function(e){
            e.preventDefault();
            var _idCustomer = $(this).data('idcustomer');

            $.ajax({
                url : "{{ url('customer/get-registry') }}",
                type : 'POST',
                data : {idCustomer : _idCustomer},
                success : function(response) {
                    $("#content-model").html(response.html);
                }
            });
        });

        $(".ajxDeleteCustomer").click(function(e){
            e.preventDefault();
            var _idCustomer = $(this).data('idcustomer');

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
                        url : "{{ url('customer/delete') }}",
                        type : 'POST',
                        data : {idCustomer : _idCustomer},
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
            var _idCustomer = $(this).data('idcustomer');

            $.ajax({
                url : "{{ url('customer/change-state') }}",
                type : 'POST',
                data : {idCustomer : _idCustomer},
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

$(".new-customer").click(function(e){
    e.preventDefault();

    $.ajax({
        url : "{{ url('customer/get-registry') }}",
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