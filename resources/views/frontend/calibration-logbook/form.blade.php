<div class="card-body">
    <form id="form-modalAction" class="form"
        action="{{ $calLogBook->id ? route('calibration-logbooks.update', ['product' => $product->id, 'calibration_logbook' => $calLogBook->id]) : route('calibration-logbooks.store', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if ($calLogBook->id)
            @method('PUT')
        @endif
        <input type="hidden" name="id" id="calLogBookId" value="{{ $calLogBook->id }}">
        <input type="hidden" name="product_id" id="productId" value="{{ $product->id }}">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="date_log" class="form-label">Tanggal Kalibrasi</label>
                    <div class="input-group" data-target-input="nearest" id="date_log">
                        <input type="text" class="form-control datetimepicker-input" placeholder="DD/MM/YYYY" data-target="#date_log" name="date_log" id="date" value="{{{ \Carbon\Carbon::parse($calLogBook->date)->format('d/m/Y') ?? '' }}}">
                        <div class="btn border" data-target="#date_log" data-toggle="datetimepicker">
                            <i class="far fa-calendar-alt"></i>
                        </div>
                    </div>
                    <small class="text-danger" id="date_log-error"></small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="technician" class="form-label">Teknisi Kalibrasi </label>
                    <input type="text" placeholder="Teknisi Kalibrasi" name="technician" class="form-control" id="technician" value="{{ $calLogBook->technician }}">
                    <small class="text-danger" id="technician-error"></small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="institution" class="form-label">Instansi </label>
                    <input type="text" placeholder="Instansi" name="institution" class="form-control" id="institution" value="{{ $calLogBook->institution }}">
                    <small class="text-danger" id="institution-error"></small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="document" class="form-label">Dokumen Kalibrasi </label>
                    <input type="file" placeholder="Dokumen Kalibrasi" name="document" class="form-control" id="document" value="" accept=".pdf">
                    <small class="text-danger" id="document-error"></small>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        // var dateValue = $('#date').val();
        // var formattedDate = moment(dateValue, 'DD/MM/YYYY').isValid() ? moment(dateValue, 'DD/MM/YYYY') : moment();

        $('#date_log').datetimepicker({
            format: 'DD/MM/YYYY',
            defaultDate: moment(),
        });

        $('#date').on('input', function() {
            var inputValue = this.value;
            this.value = inputValue.replace(/[^0-9]/g, '');

            if (this.value.length > 2) {
                this.value = this.value.slice(0, 2) + '/' + this.value.slice(2, 4) + '/' + this.value.slice(4, 8);
            }

        });
    })
</script>
