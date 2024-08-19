<div class="card-body">
    <form id="form-modalAction" class="form"
        action="{{ $productImage->id ? route('admin.product-images.update', $productImage->id) : route('admin.product-images.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if ($productImage->id)
            @method('PUT')
        @endif
        <input type="hidden" name="imageId" id="imageId" value="{{ $productImage->id }}">
        <input type="hidden" name="product_id" id="product_id" value="{{ $product->id }}">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <div class="form-group">
                    @if ($productImage->url)
                        <img src="{{ asset( $productImage->url) }}" alt="Product Image" class="img-thumbnail" width="450">
                    @endif
                </div>
            </div>
            <div class="col-md-8 mt-2">
                <div class="form-group">
                    <label for="image">Image</label>
                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" value="{{ old('image', $productImage->url ?? '') }}" @required(true)>
                    <small class="text-danger" id="image-error"></small>
                </div>
            </div>
        </div>
    </form>
</div>
