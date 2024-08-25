@extends('layouts.administrator.master')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="fw-bold">{{ $title ?? '' }}</h4>
                @can('create product-categories')
                    <button type="button" name="Add" class="btn btn-primary btn-sm" id="createCategory">
                        <i class="ti-plus"></i>
                        Tambah Data
                    </button>
                @endcan
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive text-left">
                <table class="table table-bordered dataTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>isActive</th>
                            <th>Created</th>
                            <th>Updated</th>
                            <th width="100px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <x-modal id="modalAction" title="Modal title" size="lg"></x-modal>
@endsection

@push('js')
    <script type="text/javascript">
        $(function() {
            // ajax table
            var table = $('.dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.product-categories.index') }}",
                columnDefs: [{
                    "targets": "_all",
                    "className": "text-start"
                }],
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'isActive',
                        name: 'isActive'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'updated_at',
                        name: 'updated_at'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            // create
            $('#createCategory').click(function() {
                $.get("{{ route('admin.product-categories.create') }}", function(response) {
                    $('#modalAction .modal-title').html('Add Product Category');
                    $('#modalAction .modal-body').html(response);

                    $('#modalAction').modal('show');
                })
            })

            // edit
            $('body').on('click', '.editCategory', function() {
                var categoryId = $(this).data('id');
                $.get("{{ route('admin.product-categories.index') }}" + '/' + categoryId + '/edit', function(response) {
                    $('#modalAction .modal-title').html('Edit Category');
                    $('#modalAction .modal-body').html(response);

                    $('#modalAction').modal('show');
                })
            });

            // delete
            $('body').on('click', '.deleteCategory', function() {
                var categoryId = $(this).data('id');
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
                            url: "{{ url('admin/product-categories') }}/" + categoryId,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                table.draw();
                                showToast('success', response.message);
                            },
                            error: function(response) {
                                var errorMessage = response.responseJSON
                                    .message;
                                showToast('error',
                                    errorMessage);
                            }
                        });
                    }
                });
            });

            // save
            $('#save-modal').click(function(e) {
                e.preventDefault();
                $(this).html('Sending..');
                $(this).addClass('disabled');
                var id = $('#categoryId').val();
                var url = '{{ url('admin/product-categories') }}';
                
                if (id) {
                    url += `/${id}`;
                    type = "PUT";
                }

                $.ajax({
                    data: $('#form-modalAction').serialize(),
                    url: url,
                    type: "POST",
                    dataType: 'json',
                    success: function(response) {
                        $('#modalAction').modal('hide');
                        table.draw();
                    
                        console.log('Server response:', response); // Debugging
                        if (response.message) {
                            showToast('success', response.message);
                        } else {
                            console.error('No message in response');
                        }
                    
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
