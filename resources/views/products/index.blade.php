<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"/>
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>
<body>
    
<div class="container mt-5">
    <h2 class="mb-4">Products <a class="btn btn-primary create-product" style="float:right;" href="javascript:void(0);">Create Product</a></h2>
    <table class="table table-bordered yajra-datatable">
        <thead>
            <tr>
                <th>No</th>
                <th>Product Name</th>
                <th>Product Price</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<!-- Add Product Modal -->
<div class="modal fade" id="addProduct">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Create Product</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body productData">
                
            </div>
        </div>
    </div>
</div>

<!-- Edit Product Modal -->
<div class="modal fade" id="editProduct">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Product</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body productEditData">
                
            </div>
        </div>
    </div>
</div>
</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>

<script type="text/javascript">
    /* create product */
    $('.create-product').on('click',function(){
        $('#addProduct').modal();
        $.ajax({
            url: '/products/create',
            type: 'GET',
            data: {},
            success: function (data) {
                $('.productData').html(data)
            }
        });
    });

    /* Edit product */
    $(document).on('click', '.edit-product', function(){
        var product_id = $(this).data('id');
        $('#editProduct').modal();
        $.ajax({
            url: '/products/'+product_id+'/edit',
            type: 'GET',
            data: {},
            success: function (data) {
                $('.productEditData').html(data)
            }
        });
    });

    $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(function () {
    
        var table = $('.yajra-datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url('products') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'product_name', name: 'product_name'},
                {data: 'product_price', name: 'product_price'},
                {data: 'product_description', name: 'product_description'},
                {
                    data: 'action', 
                    name: 'action', 
                    orderable: true, 
                    searchable: true
                },
            ]
        });
    
    });

    /* Deleting products */
     $(document).on('click', '.delete-product', function(){
        if (confirm("Are you sure you want to proceed with product deletion?") == true) {
            var id = $(this).data('id');
            // ajax
            $.ajax({
            type:"POST",
            url: "{{ url('delete-products') }}",
            data: { id: id},
            dataType: 'json',
            success: function(res){
                var oTable = $('.yajra-datatable').dataTable();
                oTable.fnDraw(false);
            }
            });
        }
    });
</script>
</html>