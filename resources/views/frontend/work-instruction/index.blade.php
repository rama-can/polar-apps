<x-front-layout :title="$title">
    @push('styles')
    <style>
        #pdf-viewer {
            width: 100%;
            height: 100vh; /* Atau sesuaikan dengan tinggi yang diinginkan */
            overflow: auto;
        }

        canvas {
            width: 100% !important;
            height: auto !important;
        }
    </style>
    @endpush
    <div class="container mt-5">
        <h1 class="text-center">{{ $title ?? '' }}</h1>
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center align-items-md-center mt-4">
                    <x-button-back></x-button-back>
                    <p class="fw-semibold mb-2 mb-md-0 text-center">{{ $product->name ?? '' }}</p>
                    @can('create front-work-instructions')
                    @if ($workInstruction)
                        <!-- Jika sudah ada WorkInstruction, tampilkan tombol Edit -->
                        <button type="button" name="Edit" class="btn btn-warning btn-sm" id="editWorkIns">
                            <i class="ti-pencil-alt"></i>
                            Edit Data
                        </button>
                    @else
                        <!-- Jika belum ada WorkInstruction, tampilkan tombol Tambah -->
                        <button type="button" name="Add" class="btn btn-primary btn-sm" id="createWorkIns">
                            <i class="ti-plus"></i>
                            Tambah Data
                        </button>
                    @endif
                    @endcan
                </div>
            </div>
        </div>
        <div class="col-md-12">
            {{-- <div class="d-flex justify-content-center">
                @if ($workInstruction)
                    <!-- Jika sudah ada WorkInstruction, tampilkan tombol Edit -->
                    <button type="button" name="Edit" class="btn btn-warning btn-sm" id="editWorkIns">
                        <i class="ti-pencil-alt"></i>
                        Edit Data
                    </button>
                @else
                    <!-- Jika belum ada WorkInstruction, tampilkan tombol Tambah -->
                    <button type="button" name="Add" class="btn btn-primary btn-sm" id="createWorkIns">
                        <i class="ti-plus"></i>
                        Tambah Data
                    </button>
                @endif
            </div> --}}
        </div>
        @if ($workInstruction && $workInstruction->file)
            <div class="col-md-12 mt-4 container">
                <div class="card">
                    <div class="card-header">
                        {{-- <h5 class="card-title text-center">Work Instruction PDF</h5> --}}
                    </div>
                    <div class="card-body">
                        <div id="pdf-viewer" class="embed-responsive" loading="lazy"
                        title="PDF-file" type="application/pdf">
                            <!-- PDF.js viewer will be rendered here -->
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <x-modal id="modalAction" title="Modal title" size="lg"></x-modal>
    @push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
    @if ($workInstruction && $workInstruction->file)
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';

            const url = '{{ asset($workInstruction->file) }}';

            // Asynchronous download of PDF
            pdfjsLib.getDocument(url).promise.then(pdf => {
                const container = document.getElementById('pdf-viewer');

                function renderPage(num) {
                    // Fetch the page
                    pdf.getPage(num).then(page => {
                        const scale = 1.5;
                        const viewport = page.getViewport({ scale });

                        // Prepare canvas using PDF page dimensions
                        const canvas = document.createElement('canvas');
                        const context = canvas.getContext('2d');
                        canvas.height = viewport.height;
                        canvas.width = viewport.width;
                        container.appendChild(canvas);

                        // Render PDF page into canvas context
                        const renderContext = {
                            canvasContext: context,
                            viewport: viewport
                        };
                        page.render(renderContext);
                    });
                }

                // Loop through all the pages
                for (let i = 1; i <= pdf.numPages; i++) {
                    renderPage(i);
                }
            });
        });
    </script>
    @endif

    <script>
        // create
        $('#createWorkIns').click(function() {
            $.get("{{ route('work-instructions.create', $product->id) }}", function(response) {
                $('#modalAction .modal-title').html('Add Work Instruction');
                $('#modalAction .modal-body').html(response);

                $('#modalAction').modal('show');
            })
        })

        // edit
        @if ($workInstruction)
        $('#editWorkIns').click(function() {
            $.get("{{ route('work-instructions.edit', ['product' => $product->id, 'work_instruction' => $workInstruction->id]) }}", function(response) {
                $('#modalAction .modal-title').html('Edit Work Instruction');
                $('#modalAction .modal-body').html(response);
                $('#modalAction').modal('show');
            });
        });
        @endif

        // Save
        $('#save-modal').click(function(e) {
            e.preventDefault();
            $(this).html('Sending..');
            $(this).addClass('disabled');
            var id = $('#fileId').val();
            var productId = $('#productId').val();
            var formData = new FormData($('#form-modalAction')[0]);
            var url = '{{ url('${productId}/work-instructions') }}';

            if (id) {
                url += `/${id}`;
            }

            $.ajax({
                data: formData,
                url: url,
                type: "POST",
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    $('#modalAction').modal('hide');
                    showToast('success', response.message);
                    $('#save-modal').html('Save');
                    $('#save-modal').removeClass('disabled');
                    location.reload();
                },
                error: function(response) {
                    $('#save-modal').html('Save');
                    $('#save-modal').removeClass('disabled');
                    var message = response.responseJSON.message || 'Terjadi kesalahan';
                    showToast('warning', message);
                    var errors = response.responseJSON.errors;
                    if (errors) {
                        Object.keys(errors).forEach(function(key) {
                            var errorMessage = errors[key][0];
                            $('#' + key).siblings('.text-danger').text(
                                errorMessage);
                        });
                    }
                }
            });
        });
    </script>
    @endpush
</x-front-layout>
