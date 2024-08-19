<div class="card-body">
    <form id="form-modalAction" class="form"
        action="{{ $productCategory->id ? route('admin.product-categories.update', $productCategory->id) : route('admin.product-categories.store') }}" method="POST">
        @csrf
        @if ($productCategory->id)
            @method('PUT')
        @endif
        <input type="hidden" name="categoryId" id="categoryId" value="{{ $productCategory->id }}">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" placeholder="Input Here" name="name" class="form-control" id="name"
                        value="{{ $productCategory->name }}">
                    <small class="text-danger" id="name-error"></small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="is_active" class="form-label">IsActive?</label>
                    <select class="form-select select2" name="is_active" id="is_active">
                        <option value="1" {{ $productCategory->is_active == '1' ? 'selected' : '' }}>
                            Active
                        </option>
                        <option value="0" {{ $productCategory->is_active == '0' ? 'selected' : '' }}>
                            Inactive
                        </option>
                    </select>
                    <small class="text-danger" id="is_active-error"></small>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap-5',
            placeholder: 'Select an option',
            allowClear: true,
            dropdownParent: $("#modalAction")
        });
    })
</script>
