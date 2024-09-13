@extends('layouts.administrator.master')
@push('styles')
<style>
</style>
@endpush
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <h4 class="fw-bold">{{ $title ?? '' }}</h4>
            @can('create work-instructions')
            <div class="d-flex flex-wrap">
                @if ($workInstruction)
                    <button type="button" name="Edit" class="btn btn-warning btn-sm m-1 w-100 w-md-auto" id="editWorkIns">
                        <i class="ti-pencil-alt"></i>
                        Edit Data
                    </button>
                    <button type="button" name="Delete" class="btn btn-danger btn-sm m-1 w-100 w-md-auto" id="deleteWorkIns">
                        <i class="ti-trash"></i>
                        Delete
                    </button>
                @else
                    <button type="button" name="Add" class="btn btn-primary btn-sm m-1 w-100 w-md-auto" id="createWorkIns">
                        <i class="ti-plus"></i>
                        Tambah Data
                    </button>
                @endif
            </div>
            @endcan
        </div>
    </div>
    @if ($workInstruction && $workInstruction->file)
        <div class="col-md-8 mt-4 container">
            <div class="card">
                <div class="card-body">
                    <iframe src="{{ asset($workInstruction->file) }}" style="width: 100%; height: 65vh;" frameborder="0" loading="lazy" title="PDF-file" type="application/pdf"></iframe>
                </div>
            </div>
        </div>
    @endif
</div>
<x-modal id="modalAction" title="Modal title" size="lg"></x-modal>
@endsection
@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>

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
        @if ($workInstruction && $workInstruction->file)
        $('#editWorkIns').click(function() {
            $.get("{{ route('admin.work-instructions.edit', ['product' => $product->id, 'work_instruction' => $workInstruction->id]) }}", function(response) {
                $('#modalAction .modal-title').html('Edit Work Instruction');
                $('#modalAction .modal-body').html(response);
                $('#modalAction').modal('show');
            });
        });

        $('body').on('click', '#deleteWorkIns', function() {
                // var roleId = $(this).data('id');
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
                            url: "{{ route('admin.work-instructions.destroy', ['product' => $product->id, 'work_instruction' => $workInstruction->id]) }}",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                showToast('success', response.message);
                                location.reload();
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
        @endif

        // Save
        $('#save-modal').click(function(e) {
            e.preventDefault();
            $(this).html('Sending..');
            $(this).addClass('disabled');
            var id = $('#fileId').val();
            var productId = $('#productId').val();
            var formData = new FormData($('#form-modalAction')[0]);
            var url = id ? `{{ url('admin/${productId}/work-instructions') }}/${id}` : `{{ url('admin/${productId}/work-instructions') }}`;
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
    </script>
@endpush
