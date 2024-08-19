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
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title">Usage Logbook</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.export.usage-logbook') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="from_date_usage" class="form-label">From Date</label>
                            <div class="input-group date" data-provide="datepicker">
                                <input type="text" class="form-control datepicker" placeholder="dd/mm/yyyy" name="from_date_usage" value="">
                                <div class="btn border">
                                    <i class="far fa-calendar-alt"></i>
                                </div>
                            </div>
                            <small class="text-danger" id="from_date_usage-error"></small>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="to_date_usage" class="form-label">To Date</label>
                            <div class="input-group date" data-provide="datepicker">
                                <input type="text" class="form-control datepicker" placeholder="dd/mm/yyyy" name="to_date_usage" value="">
                                <div class="btn border">
                                    <i class="far fa-calendar-alt"></i>
                                </div>
                            </div>
                            <small class="text-danger" id="to_date_usage-error"></small>
                        </div>
                    </div>
                    <div class="col-md-8 text-end">
                        <button type="submit" class="btn btn-primary btn-sm">Export</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Form for Exporting Calibration Logbook -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Calibration Logbook</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.export.calibration-logbook') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="from_date_calibration" class="form-label">From Date</label>
                            <div class="input-group date" data-provide="datepicker">
                                <input type="text" class="form-control datepicker" placeholder="dd/mm/yyyy" name="from_date_calibration" value="">
                                <div class="btn border">
                                    <i class="far fa-calendar-alt"></i>
                                </div>
                            </div>
                            <small class="text-danger" id="from_date_calibration-error"></small>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="to_date_calibration" class="form-label">To Date</label>
                            <div class="input-group date" data-provide="datepicker">
                                <input type="text" class="form-control datepicker" placeholder="dd/mm/yyyy" name="to_date_calibration" value="">
                                <div class="btn border">
                                    <i class="far fa-calendar-alt"></i>
                                </div>
                            </div>
                            <small class="text-danger" id="to_date_calibration-error"></small>
                        </div>
                    </div>
                    <div class="col-md-8 text-end">
                        <button type="submit" class="btn btn-primary btn-sm">Export</button>
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

            $('form').on('submit', function(event) {
                var isValid = true;

                // Validasi untuk form Usage Logbook
                if ($(this).find('input[name="from_date_usage"]').length) {
                    var fromDateUsage = $('input[name="from_date_usage"]').val();
                    var toDateUsage = $('input[name="to_date_usage"]').val();

                    if (!fromDateUsage) {
                        $('#from_date_usage-error').text('From Date is required.');
                        $('input[name="from_date_usage"]').addClass('is-invalid');
                        isValid = false;
                    } else {
                        $('#from_date_usage-error').text('');
                        $('input[name="from_date_usage"]').removeClass('is-invalid');
                    }

                    if (!toDateUsage) {
                        $('#to_date_usage-error').text('To Date is required.');
                        $('input[name="to_date_usage"]').addClass('is-invalid');
                        isValid = false;
                    } else {
                        $('#to_date_usage-error').text('');
                        $('input[name="to_date_usage"]').removeClass('is-invalid');
                    }
                }

                // Validasi untuk form Calibration Logbook
                if ($(this).find('input[name="from_date_calibration"]').length) {
                    var fromDateCalibration = $('input[name="from_date_calibration"]').val();
                    var toDateCalibration = $('input[name="to_date_calibration"]').val();

                    if (!fromDateCalibration) {
                        $('#from_date_calibration-error').text('From Date is required.');
                        $('input[name="from_date_calibration"]').addClass('is-invalid');
                        isValid = false;
                    } else {
                        $('#from_date_calibration-error').text('');
                        $('input[name="from_date_calibration"]').removeClass('is-invalid');
                    }

                    if (!toDateCalibration) {
                        $('#to_date_calibration-error').text('To Date is required.');
                        $('input[name="to_date_calibration"]').addClass('is-invalid');
                        isValid = false;
                    } else {
                        $('#to_date_calibration-error').text('');
                        $('input[name="to_date_calibration"]').removeClass('is-invalid');
                    }
                }

                // Jika ada input yang kosong, cegah form dari pengiriman
                if (!isValid) {
                    event.preventDefault();
                }
            });

            // event listener untuk tombol kalender untuk open datepicker
            $('.input-group.date .btn').on('click', function() {
                $(this).siblings('input.datepicker').datepicker('show');
            });
        });
    </script>
@endpush
