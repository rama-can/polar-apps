@extends('layouts.administrator.master')

@section('content')
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="fw-bold">{{ $title ?? 'Export Data' }}</h4>
                </div>
            </div>
        </div>

        <!-- Form for Exporting Usage Logbook -->
        <div class="card">
            <div class="card-header">
                {{-- <h5 class="card-title">Usage Logbook</h5> --}}
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.setting.update') }}">
                    @csrf
                    {{-- <div class="form-group row mb-4">
                        <label for="site_title" class="col-sm-2 col-form-label">Site Title</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="site_title" name="settings['site_title']" placeholder="Site Title" value="{{ settings()->get('site_title') }}">
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label for="site_description" class="col-sm-2 col-form-label">Site Description</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="site_description" name="settings['site_description']" placeholder="Site Title" value="{{ settings()->get('site_description') }}">
                        </div>
                    </div> --}}
                    <div class="form-group row mb-4">
                        <label for="is_register" class="col-sm-2 col-form-label">Is Register?</label>
                        <div class="col-sm-6">
                            <select class="form-select select2" id="is_register" name="settings['is_register']">
                                <option value=""></option>
                                <option {{ settings()->get('is_register') == '1' ? 'selected' : '' }} value="1">
                                    Active
                                </option>
                                <option {{ settings()->get('is_register') == '0' ? 'selected' : '' }} value="0">
                                    Inactive
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label for="is_forgot_password" class="col-sm-2 col-form-label">Is Forgot Password?</label>
                        <div class="col-sm-6">
                            <select class="form-select select2" id="is_forgot_password" name="settings['is_forgot_password']">
                                <option value=""></option>
                                <option {{ settings()->get('is_forgot_password') == '1' ? 'selected' : '' }} value="1">
                                    Active
                                </option>
                                <option {{ settings()->get('is_forgot_password') == '0' ? 'selected' : '' }} value="0">
                                    Inactive
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-8 text-end">
                        <button type="submit" class="btn btn-primary btn-sm mt-4">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@push('js')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
            });

            // event listener untuk tombol kalender untuk open datepicker
            $('.input-group.date .btn').on('click', function() {
                $(this).siblings('input.datepicker').datepicker('show');
            });
        });
    </script>
@endpush
