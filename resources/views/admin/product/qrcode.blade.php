<div class="card-body">
    <form id="form-modalAction" class="form"
        action="" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="product_id" id="product_id" value="{{ $product->id }}">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <div class="form-group">
                    @if ($qrCode)
                        <img src="data:image/png;base64, {!!  base64_encode($qrCode) !!}" class="img-thumbnail" width="250" />
                    @endif
                </div>
            </div>
            <div class="col-md-8 mt-2 text-center">
                <div class="form-group">
                    <h5>{{ $product->name }}</h5>
                </div>
            </div>
        </div>
    </form>
</div>
