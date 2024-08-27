@extends('layouts.administrator.master')
@push('css')
<style>
.pdf-viewer-container {
    width: 100%;
    height: 75vh;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
}

.pdf-viewer-container canvas {
    max-width: 100%;
    height: auto;
    margin-bottom: 10px;
}
</style>
@endpush
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-3">
                <h4 class="fw-bold mb-3 mb-md-0">{{ $title ?? '' }}</h4>
                <div class="w-100 w-md-auto d-flex flex-column flex-md-row align-items-center gap-3">
                    <form action="{{ route('admin.export.calibration-logbook') }}" method="POST" class="d-flex flex-column flex-md-row align-items-center gap-3 w-100">
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
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="table-responsive text-left">
                <table class="table table-bordered dataTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Date</th>
                            <th>Technician</th>
                            <th>Institution</th>
                            <th>Document</th>
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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="previewDocumentModalLabel">Preview Document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="pdfViewerContainer" class="pdf-viewer-container">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
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
            var baseUrl = '{{ url('/') }}';
            var relativeUrl = $(this).data('id');

            var url = new URL(relativeUrl, baseUrl).href;

            if (!url.endsWith('.pdf')) {
                showToast('error', 'Invalid URL or file type');
                return;
            }

            if (typeof pdfjsLib === 'undefined') {
                console.error('pdfjsLib is not loaded properly.');
                return;
            }

            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';

            var loadingTask = pdfjsLib.getDocument(url);
            loadingTask.promise.then(function(pdf) {
                $('#pdfViewerContainer').html('');

                for (let pageNumber = 1; pageNumber <= pdf.numPages; pageNumber++) {
                    pdf.getPage(pageNumber).then(function(page) {
                        var viewport = page.getViewport({ scale: 1 });
                        var modalWidth = $('#previewDocumentModal .modal-body').width();
                        var scale = modalWidth / viewport.width;

                        viewport = page.getViewport({ scale: scale });

                        var canvas = document.createElement('canvas');
                        var context = canvas.getContext('2d');
                        canvas.height = viewport.height;
                        canvas.width = viewport.width;

                        var renderContext = {
                            canvasContext: context,
                            viewport: viewport
                        };
                        var renderTask = page.render(renderContext);
                        renderTask.promise.then(function () {
                            $('#pdfViewerContainer').append(canvas);
                        });
                    });
                }
            }).catch(function(error) {
                console.error('Error loading PDF:', error);
                showToast('error', 'Failed to load PDF document');
            });

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
            var url = id ? `{{ url('admin/${productId}/calibration-logbooks') }}/${id}` : `{{ url('admin/${productId}/calibration-logbooks') }}`;

            $.ajax({
                data: formData,
                url: url,
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
