@extends('layouts.administrator.master')

@section('content')
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                <h4 class="fw-bold mb-3 mb-md-0">{{ $title ?? '' }}</h4>
                {{-- create form export filter date --}}
                {{-- <form method="POST" action="{{ route('admin.export.usage-logbook') }}"enctype="multipart/form-data" class="d-flex flex-column flex-md-row align-items-center">
                    @csrf
                    <div class="d-flex flex-column flex-md-row align-items-center">
                        <div class="mb-3 mb-md-0 me-md-2">
                            <label for="from_date" class="form-label">Tanggal Mulai</label>
                            <div class="input-group date" data-provide="datepicker">
                                <input type="text" class="form-control datepicker" placeholder="dd/MM/yyyy" name="from_date" value="">
                                <div class="btn border">
                                    <i class="far fa-calendar-alt"></i>
                                </div>
                            </div>
                            <small class="text-danger" id="from_date-error"></small>
                        </div>
                        <div class="mb-3 mb-md-0 me-md-2">
                            <label for="to_date" class="form-label">Tanggal Selesai</label>
                            <div class="input-group date" data-provide="datepicker">
                                <input type="text" class="form-control datepicker" placeholder="dd/MM/yyyy" name="to_date" value="">
                                <div class="btn border">
                                    <i class="far fa-calendar-alt"></i>
                                </div>
                            </div>
                            <small class="text-danger" id="to_date-error"></small>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm mt-4">
                            <i class="ti-export"></i> Export
                        </button>
                    </div>
                </form> --}}
                @can('create products')
                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm mt-3 mt-md-0">
                        <i class="ti-plus"></i> Add Product
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
                            <th>Category</th>
                            <th>Status</th>
                            <th>Modules</th>
                            <th>QRCode</th>
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

    <x-modal id="modalAction" title="Modal title" size="lg"></x-modal>
@endsection

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {
            // Inisialisasi datepicker untuk semua elemen dengan kelas .datepicker
            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true
            });

            // event listener untuk tombol kalender untuk open datepicker
            $('.input-group.date .btn').on('click', function() {
                $(this).siblings('input.datepicker').datepicker('show');
            });
        });


        $(function() {
            var url;

            @if(isset($category))
                url = "{{ route('admin.product-categories.products', $category->slug) }}";
            @else
                url = "{{ route('admin.products.index') }}";
            @endif
            // ajax table
            var table = $('.dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: url,
                    error: function(xhr, error, code) {
                        console.log(xhr.responseText);
                        alert('AJAX Error: ' + xhr.responseText);
                    }
                },
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
                    { data: 'name', name: 'name' },
                    { data: 'category', name: 'category' },
                    { data: 'status', name: 'status' },
                    { data: 'modules', name: 'modules' },
                    { data: 'QRcode', name: 'QRcode' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });

            // show qr code
            $('body').on('click', '.showQrCodeProduct', function() {
                var productId = $(this).data('id');
                // console.log(productId);
                $.get("{{ url('admin/qrcode/generate') }}/" + productId, function(response) {
                    $('#modalAction .modal-title').html('Generate QR code for this data');
                    $('#modalAction .modal-body').html(response);

                    $('#modalAction').modal('show');
                });
            });

            // download qr code
            $('#save-modal').click(function(e) {
                e.preventDefault();
                var id = $('#product_id').val();
                window.location.href = "{{ url('admin/qrcode/download') }}/" + id;
                $('#modalAction').modal('hide');
                showToast('success', response.message);
            });

            // delete
            $('body').on('click', '.deleteProduct', function() {
                var productId = $(this).data('id');
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
                            url: "{{ url('admin/products') }}/" + productId,
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
        });
    </script>
@endpush
