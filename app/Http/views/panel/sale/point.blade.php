@extends($layout)

@section('content-body')
<section class="content-header">
    <button class="new-product btn btn-primary btn-sm">Producto &nbsp;&nbsp;&nbsp;<i class="fa fa-plus"></i></button>
    <button class="new-client btn btn-primary btn-sm">Cliente &nbsp;&nbsp;&nbsp;<i class="fa fa-plus"></i></button>

    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="breadcrumb-item active">Pedido</li>
    </ol>
</section>

<!-- Main content -->
<section class="invoice">
    <!-- title row -->
    <div class="row">
        <div class="col-md-12">
            <h2 class="page-header">
                <i class="fa fa-user"></i> {{$clientName}}
                <small class="pull-right">Fecha: {{$today}}</small>
            </h2>
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

    <!-- Table row -->
    <div class="row">
        <div class="col-xs-12 table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Cant</th>
                        <th>Producto</th>
                        <th>Código #</th>
                        <th>Descripción</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    {!! $details !!}
                </tbody>
            </table>
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

    <div class="row">
        <!-- /.col -->
        <div class="col-xs-6">
            <div class="table-responsive">
                {!! $summary !!}
            </div>
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

    <!-- this row will not appear when printing -->
    <div class="row no-print">
        <div class="col-xs-12">
            <button type="button" class="process btn btn-success pull-right"><i class="fa fa-credit-card"></i> Procesar Pedido</button>
        </div>
    </div>
</section>
<!-- /.content -->
@stop

@section('javascript')
@parent
<script type="text/javascript">

$(document).ready(function(){

    $(".new-client").click(function(e){
        e.preventDefault();

        $.ajax({
            url : "{{ url('customer/get-customer') }}",
            type : 'POST',
            data : null,
            success : function(response) {
                $("#content-model").html(response.html);
            }
        });
    });

    $(".new-product").click(function(e){
        e.preventDefault();

        $.ajax({
            url : "{{ url('product/get-products') }}",
            type : 'POST',
            data : null,
            success : function(response) {
                $("#content-model").html(response.html);
            }
        });
    });

    $(".process").click(function(e){
        e.preventDefault();

        $.ajax({
            url : "{{ url('salepoint/process') }}",
            type : 'POST',
            data : null,
            success : function(response) {

                if (response.status == STATUS_OK)
                {
                    toastr.success('Pedido procesado con exito.!');
                     window.location.reload();
                }
                else
                {
                    toastr.error('Error en operación.');
                }
            }
        });
    });
});

</script>
@endsection