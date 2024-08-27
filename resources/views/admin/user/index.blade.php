@extends('layouts.administrator.master')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="fw-bold">{{ $title }}</h4>
                @can('create users')
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm" id="createUser">
                        <i class="ti-plus"></i>
                        Add Data
                    </a>
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
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>isActive?</th>
                            <th>Gender</th>
                            <th width="100px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript">
        $(function() {
            // ajax table
            var table = $('.dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.users.index') }}",
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
                        data: 'fullname',
                        name: 'fullname'
                    },
                    {
                        data: 'username',
                        name: 'username',
                    },
                    {
                        data: 'email',
                        name: 'email',
                    },
                    {
                        data: 'role',
                        name: 'role'
                    },
                    {
                        data: 'isActive',
                        name: 'isActive'
                    },
                    {
                        data: 'gender',
                        name: 'gender'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            // delete
            $('body').on('click', '.deleteUser', function() {
                var userId = $(this).data('id');
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
                            type: "POST",
                            url: "{{ url('admin/users') }}/" + userId,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                _method: 'DELETE'
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



            // search permission
            $(document).on('input', '#searchPermission', function() {
                var searchValue = $(this).val().toLowerCase();
                var permissionItems = $('.permission-item');
                var showSelectAll = false;

                permissionItems.each(function() {
                    var label = $(this).find('.form-check-label');
                    var permissionName = label.text().toLowerCase();

                    if (permissionName.includes(searchValue)) {
                        $(this).show();
                        showSelectAll = true;
                    } else {
                        $(this).hide();
                    }
                });

                var selectAllCheckbox = $('#checkAll');
                if (selectAllCheckbox.length > 0) {
                    selectAllCheckbox.closest('.row').css('display', showSelectAll ? 'block' : 'none');
                }
            });


            $('#save-modal').click(function(e) {
                e.preventDefault();
                $(this).html('Sending..');

                $.ajax({
                    data: $('#form-modalAction').serialize(),
                    url: `{{ route('admin.permissions.store') }}`,
                    type: "POST",
                    dataType: 'json',
                    success: function(response) {
                        $('#modalAction').modal('hide');
                        table.draw();
                        if (response.status == true) {
                            showToast('success', response.message);
                        } else {
                            showToast('error', response.message);
                        }
                        $('#save-modal').html('Save');
                    },
                    error: function(response) {
                        if (response.responseJSON && response.responseJSON.errors) {
                            var errors = response.responseJSON.errors;
                            if (errors.hasOwnProperty('permissions')) {
                                var errorMessage = errors['permissions'][0];
                                $('#permissions-error').removeClass('d-none');
                                $('#permissions-error').text(errorMessage);
                            }
                        }

                        $('#save-modal').html('Save');
                    }
                });
            });

        });
    </script>
@endpush
