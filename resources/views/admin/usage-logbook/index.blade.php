@extends('layouts.administrator.master')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-3">
                <h4 class="fw-bold mb-3 mb-md-0">{{ $title ?? '' }}</h4>
                <div class="w-100 w-md-auto d-flex flex-column flex-md-row align-items-center gap-3">
                    <form action="{{ route('admin.export.usage-logbook') }}" method="POST" class="d-flex flex-column flex-md-row align-items-center gap-3 w-100">
                        @csrf
                        <input type="text" name="product_id" id="product_id" value="{{ $product->id }}" hidden>
                        <div class="flex-grow-1">
                            <label for="from_date" class="form-label">From Date</label>
                            <div class="input-group date" data-provide="datepicker">
                                <input type="text" class="form-control datepicker" placeholder="yyyy/mm/dd" name="from_date" value="" required>
                                <div class="btn border">
                                    <i class="far fa-calendar-alt"></i>
                                </div>
                            </div>
                            @if ($errors->has('from_date'))
                                <small class="text-danger">{{ $errors->first('from_date') }}</small>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <label for="to_date" class="form-label">To Date</label>
                            <div class="input-group date" data-provide="datepicker">
                                <input type="text" class="form-control datepicker" placeholder="yyyy/mm/dd" name="to_date" value="" required>
                                <div class="btn border">
                                    <i class="far fa-calendar-alt"></i>
                                </div>
                            </div>
                            @if ($errors->has('to_date'))
                                <small class="text-danger">{{ $errors->first('to_date') }}</small>
                            @endif
                        </div>
                        <div class="flex-shrink-0 mt-3 mt-md-4">
                            <button type="submit" class="btn btn-primary btn-sm">Export</button>
                        </div>
                    </form>
                    <div class="mt-3 mt-md-0">
                        @can('create product-categories')
                            <button type="button" name="Add" class="btn btn-primary btn-sm" id="createLogbook">
                                <i class="ti-plus"></i> Add Data
                            </button>
                        @endcan
                    </div>
                </div>
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
                            <th>Date</th>
                            <th>User Name</th>
                            <th>Status</th>
                            <th>Total Duration</th>
                            <th>Temperature</th>
                            <th>RH</th>
                            <th>Note</th>
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
            responsive: true,
            ajax: {
                url: "{{ route('admin.usage-logbooks.index', $product->id) }}",
                error: function(xhr, error, code) {
                    console.log(xhr.responseText);
                    alert('AJAX Error: ' + xhr.responseText);
                }
            },
            columnDefs: [
                {
                    "targets": "_all",
                    "className": "text-start"
                },
                {
                    "targets": [0,1,4,5,6,7,8],
                    "orderable": false,
                    "searchable": false,
                },
            ],
            columns: [
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                { data: 'date', name: 'date' },
                { data: 'name', name: 'name' },
                { data: 'status', name: 'status' },
                { data: 'total_duration', name: 'total_duration' },
                { data: 'temperature', name: 'temperature' },
                { data: 'rh', name: 'rh' },
                { data: 'note', name: 'note' },
                { data: 'action', name: 'action' }
            ]
        });

        // create
        $('#createLogbook').click(function() {
            $.get("{{ route('admin.usage-logbooks.create', $product->id) }}", function(response) {
                $('#modalAction .modal-title').html('Tambah Logbook');
                $('#modalAction .modal-body').html(response);

                $('#modalAction').modal('show');
            })
        })

        // edit
        $('body').on('click', '.editLogbook', function() {
            var logbookId = $(this).data('id');
            var productId = '{{ $product->id }}'
            $.get(`{{ url('admin/${productId}/usage-logbooks') }}/${logbookId}/edit`, function(response) {
                $('#modalAction .modal-title').html('Edit Logbook');
                $('#modalAction .modal-body').html(response);

                $('#modalAction').modal('show');
            })
        });

        // delete
        $('body').on('click', '.deleteLogbook', function() {
            var logbookId = $(this).data('id');
            var productId = '{{ $product->id }}'
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
                        url: `{{ url('admin/${productId}/usage-logbooks') }}/${logbookId}`,
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
            var id = $('#usageLogBook').val();
            var productId = '{{ $product->id }}';
            var url = id ? `{{ url('admin/${productId}/usage-logbooks') }}/${id}` : `{{ url('admin/${productId}/usage-logbooks') }}`;

            $.ajax({
                data: $('#form-modalAction').serialize(),
                url: url,
                type: "POST",
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
