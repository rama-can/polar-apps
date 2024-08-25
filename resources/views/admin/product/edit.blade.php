@extends('layouts.administrator.master')
@section('content')
    <x-form-section title="{{ $title }}">

        <form method="POST" action="{{ route('admin.products.update', $product->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row mt-2">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Name </label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" value="{{ old('name', $product->name) }}" @required(true)>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="product_category_id">Category </label>
                        <select class="form-select select2 @error('product_category_id') is-invalid @enderror" id="product_category_id" name="product_category_id" @required(true)>
                            <option value=""></option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ $product->category->name == $category->name ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('product_category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="status">Status </label>
                        <select class="form-select select2 @error('status') is-invalid @enderror" id="status" name="status" @required(true)>
                            <option value=""></option>
                            <option {{ $product->status == '1' ? 'selected' : '' }} value="1"
                                {{ old('status') == 'Active' ? 'selected' : '' }}>
                                Active
                            </option>
                            <option {{ $product->status == '0' ? 'selected' : '' }} value="0"
                                {{ old('status') == 'Inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="description">Description</label>
                        <x-classic-ckeditor editor="classic" name="description" :value="$product->description ?? ''" />
                        @if($errors->has('description'))
                            <div class='text-danger mt-2'>* {{ $errors->first('description') }}</div>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="content">Content</label>
                        <x-ckeditor name="content" :value="$product->content ?? ''" />
                        @if($errors->has('content'))
                            <div class='text-danger mt-2'>* {{ $errors->first('content') }}</div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-md-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="fw-bold"></h4>
                        @can('create product-images')
                            <button type="button" name="Add" class="btn btn-primary btn-sm" id="createImage">
                                <i class="ti-plus"></i>
                                Add Images
                            </button>
                        @endcan
                    </div>
                    <div class="table-responsive text-left">
                        <table class="table table-bordered dataTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Image</th>
                                    <th>Created</th>
                                    <th width="100px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <x-btn-submit-form />

        </form>

    </x-form-section>
    <x-modal id="modalAction" title="Modal title" size="lg"></x-modal>
@endsection
@push('js')
<script>
$(function() {
    var table = $('.dataTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('admin.product-images.index') }}",
            data: function (d) {
                d.product_id = {{ $product->id }};
            },
            error: function(xhr, error, code) {
                console.log(xhr.responseText);
                alert('AJAX Error: ' + xhr.responseText);
            }
        },
        columns: [
            {
                data: 'id',
                name: 'id',
                orderable: true,
                searchable: false,
                render: function(data, type, full, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            { data: 'url', name: 'url', render: function(data, type, full, meta) { return `<img src="${data}" alt="Image" width="50" height="50">`; }},
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        columnDefs: [{
            "targets": "_all",
            "className": "text-start"
        }],
        pageLength: 3,
        lengthMenu: [ [3, 10, 25], [3, 10, 25] ],
    });

    // Create
    $('#createImage').click(function() {
        var productId = {{ $product->id }};
        $.get("{{ route('admin.product-images.create') }}", { id: productId }, function(response) {
            $('#modalAction .modal-title').html('Add Image Product');
            $('#modalAction .modal-body').html(response);

            $('#modalAction').modal('show');
        });
    })

    // Edit
    $('body').on('click', '.editImage', function() {
        var imageId = $(this).data('id');
        $.get("{{ route('admin.product-images.index') }}" + '/' + imageId + '/edit', function(response) {
            $('#modalAction .modal-title').html('Edit Image');
            $('#modalAction .modal-body').html(response);

            $('#modalAction').modal('show');
        })
    });

    // Delete
    $('body').on('click', '.deleteImage', function() {
        var imageId = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "Deleted data cannot be restored!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#82868',
            confirmButtonText: 'Yes, delete!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "DELETE",
                    url: "{{ url('admin/product-images') }}/" + imageId,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        table.draw();
                        showToast('success', response.message);
                    },
                    error: function(response) {
                        var errorMessage = response.responseJSON.message;
                        showToast('error', errorMessage);
                    }
                });
            }
        });
    });

    // Save
    $('#save-modal').click(function(e) {
        e.preventDefault();
        $(this).html('Sending..');
        $(this).addClass('disabled');
        var id = $('#imageId').val();
        var formData = new FormData($('#form-modalAction')[0]);
        var url = id ? `{{ url('admin/product-images/') }}/${id}` : `{{ url('admin/product-images') }}`;
        $.ajax({
            data: formData,
            url: url,
            type: "POST",
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                $('#modalAction').modal('hide');
                table.draw();
                showToast('success', response.message);
                $('#save-modal').html('Save');
                $('#save-modal').removeClass('disabled');
            },
            error: function(response) {
                var errors = response.responseJSON.errors;
                if (errors) {
                    Object.keys(errors).forEach(function(key) {
                        var errorMessage = errors[key][0];
                        $('#' + key).siblings('.text-danger').text(
                            errorMessage);
                    });
                }
                $('#save-modal').html('Save');
                $('#save-modal').removeClass('disabled');
            }
        });
    });
});
</script>
@endpush
