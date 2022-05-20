<form id="EditProductForm" name="EditProductForm" enctype="multipart/form-data">
@csrf
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12">
			<div class="form-group">
			<strong>Product Name:</strong>
			<input type="text" name="product_name" class="form-control" placeholder="Product Name" value="{{ $product->product_name }}">
			@error('product_name')
			<div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
			@enderror
			</div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12">
			<div class="form-group">
			<strong>Product Price:</strong>
			<input type="text" name="product_price" class="form-control" placeholder="Product Price" value="{{ $product->product_price }}">
			@error('email')
			<div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
			@enderror
			</div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12">
			<div class="form-group">
			<strong>Product Description:</strong>
			<textarea name="product_description" class="form-control">{{ $product->product_description}}</textarea>
			@error('product_description')
			<div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
			@enderror
			</div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12">
			<div class="form-group">
				<strong>Upload Multiple Images:</strong>
				<div class="user-image mb-3 text-center">
					@php 
						$images = json_decode($product->product_image);
					@endphp
					@foreach($images as $image)
						<img src='{{env("APP_URL")}}/storage/product-images/{{$image}}'>
					@endforeach
	                <div class="imgPreview"> </div>
	            </div>            
	            <div class="custom-file">
	                <input type="file" name="product_image[]" class="custom-file-input" id="images" multiple="multiple" >
	                <label class="custom-file-label" for="images">Choose image</label>
	            </div>
            </div>
        </div>
        <input type="hidden" name="product_id" value="{{$product->id}}">
		<button type="submit" class="btn btn-primary ml-3" id="saveBtn">Submit</button>
	</div>
</form>

<script>
        $(function() {
        // Multiple images preview with JavaScript
        var multiImgPreview = function(input, imgPreviewPlaceholder) {
            if (input.files) {
                var filesAmount = input.files.length;
                for (i = 0; i < filesAmount; i++) {
                    var reader = new FileReader();
                    reader.onload = function(event) {
                        $($.parseHTML('<img>')).attr('src', event.target.result).appendTo(imgPreviewPlaceholder);
                    }
                    reader.readAsDataURL(input.files[i]);
                }
            }
        };
        $('#images').on('change', function() {
            multiImgPreview(this, 'div.imgPreview');
        });
        });

        $('#saveBtn').click(function (e) {
	        e.preventDefault();
	        $(this).html('please wait..');

			var frm = $('#EditProductForm');
			var formData = new FormData(frm[0]);

			$.ajax({
			    type: "POST",
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                url: "/products",
                dataType: "json",
		          success: function (data) {

		              $('#EditProductForm').trigger("reset");
		              $('#editProduct').modal('hide');
		              $('.yajra-datatable').DataTable().draw();

		          },
		          error: function (data) {
		              console.log('Error:', data);
		              $('#saveBtn').html('Save Changes');
		          }
	      });
	    });    
    </script>