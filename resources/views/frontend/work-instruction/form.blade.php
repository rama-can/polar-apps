<div class="card-body">
    <form id="form-modalAction" class="form"
        action="{{ $workIns->id ? route('work-instructions.update', ['product' => $product->id, 'work_instruction' => $workIns->id]) : route('work-instructions.store', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if ($workIns->id)
            @method('PUT')
        @endif
        <input type="hidden" name="fileId" id="fileId" value="{{ $workIns->id }}">
        <input type="hidden" name="product_id" id="productId" value="{{ $product->id }}">
        <div class="row">
            <div class="col-md-8 mt-2 m-lg-4">
                <div class="form-group">
                    <label for="file">File</label>
                    <input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="file" value="{{ old('file', $workIns->file ?? '') }}" accept=".pdf" @required(true)>
                    <small class="text-danger" id="file-error"></small>
                </div>
            </div>
        </div>
    </form>
</div>
