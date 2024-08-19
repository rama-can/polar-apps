@extends('layouts.administrator.master')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="fw-bold">{{ $title ?? '' }}</h4>
                @can('create product-categories')
                    <button type="button" name="Add" class="btn btn-primary btn-sm" id="createLogbook">
                        <i class="ti-plus"></i>
                        Tambah Data
                    </button>
                @endcan
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="table-responsive text-left">
                <table class="table table-bordered dataTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Teknisi</th>
                            <th>Instansi</th>
                            <th>Dokumen</th>
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
    <!-- Modal Preview Document -->
    <div class="modal fade" id="previewDocumentModal" tabindex="-1" aria-labelledby="previewDocumentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="previewDocumentModalLabel">Preview Document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <iframe id="documentPreview" src="" style="width: 100%; height: 65vh;" frameborder="0" loading="lazy" title="PDF-file" type="application/pdf"></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
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
            responsive: true,
            ajax: {
                url: "{{ route('admin.calibration-logbooks.index', $product->id) }}",
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
            ],
            columns: [
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                { data: 'date', name: 'date' },
                { data: 'technician', name: 'technician' },
                { data: 'institution', name: 'institution' },
                { data: 'document', name: 'document' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]
        });
    // Event handler for preview button click
    $(document).on('click', '.previewDocument', function() {
        var documentId = $(this).data('id');

        // Create the URL for document preview
        var documentUrl = documentId; // Adjust the URL as needed

        // Set the src attribute of the iframe
        $('#documentPreview').attr('src', documentUrl);

        // Show the modal
        $('#previewDocumentModal').modal('show');
    });
        // create
        $('#createLogbook').click(function() {
            $.get("{{ route('admin.calibration-logbooks.create', $product->id) }}", function(response) {
                $('#modalAction .modal-title').html('Tambah Logbook Kalibrasi');
                $('#modalAction .modal-body').html(response);

                $('#modalAction').modal('show');
            })
        })

        // edit
        $('body').on('click', '.editLogbook', function() {
            var logbookId = $(this).data('id');
            var productId = '{{ $product->id }}'
            $.get(`{{ url('admin/${productId}/calibration-logbooks') }}/${logbookId}/edit`, function(response) {
                $('#modalAction .modal-title').html('Edit Logbook Kalibrasi');
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
                        url: `{{ url('admin/${productId}/calibration-logbooks') }}/${logbookId}`,
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
            var id = $('#calLogBookId').val();
            var productId = '{{ $product->id }}';
            var formData = new FormData($('#form-modalAction')[0]);
            $.ajax({
                data: formData,
                url: `{{ url('admin/${productId}/calibration-logbooks') }}/${id}`,
                type: "POST",
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    table.draw();
                    $('#modalAction').modal('hide');
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
