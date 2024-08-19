<div class="card-body">
    <form id="form-modalAction" class="form"
        action="{{ $usageLogbook->id ? route('admin.usage-logbooks.update', ['product' => $product->id, 'usage_logbook' => $usageLogbook->id]) : route('admin.usage-logbooks.store', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if ($usageLogbook->id)
            @method('PUT')
        @endif
        <input type="hidden" name="usageLogBook" id="usageLogBook" value="{{ $usageLogbook->id }}">
        <input type="hidden" name="product_id" id="productId" value="{{ $product->id }}">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="date_log" class="form-label">Tanggal</label>
                    <div class="input-group" data-target-input="nearest" id="date_log">
                        <input type="text" class="form-control datetimepicker-input" placeholder="dd/MM/yyyy" data-target="#date_log" name="date_log" id="date" value="{{ $usageLogbook->date ? $usageLogbook->date->format('d/m/Y') : '' }}">
                        <div class="btn border" data-target="#date_log" data-toggle="datetimepicker">
                            <i class="far fa-calendar-alt"></i>
                        </div>
                    </div>
                    <small class="text-danger" id="date_log-error"></small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Pengguna</label>
                    <input type="text" placeholder="Input Here" name="name" class="form-control" id="name" value="{{ $usageLogbook->name }}">
                    <small class="text-danger" id="name-error"></small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select select2 form-control" name="status" id="status">
                        <option value="" disabled selected>--- Pilih Status ---</option>
                        <option value="MAHASISWA" {{ $usageLogbook->status == 'MAHASISWA' ? 'selected' : '' }}>
                            Mahasiswa
                        </option>
                        <option value="PLP" {{ $usageLogbook->status == 'PLP' ? 'selected' : '' }}>
                            PLP
                        </option>
                        <option value="DOSEN" {{ $usageLogbook->status == 'DOSEN' ? 'selected' : '' }}>
                            Dosen
                        </option>
                        <option value="PENELITI" {{ $usageLogbook->status == 'PENELITI' ? 'selected' : '' }}>
                            Peneliti
                        </option>
                        <option value="LAINNYA" {{ $usageLogbook->status == 'LAINNYA' ? 'selected' : '' }}>
                            Lainnya
                        </option>
                    </select>
                    <small class="text-danger" id="status-error"></small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="total_duration" class="form-label">Total Durasi</label>
                    <div class="input-group date" id="total_duration" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" placeholder="HH:mm" data-target="#total_duration" name="total_duration" id="total_duration_input" value="{{ $usageLogbook->total_duration ? $usageLogbook->total_duration->format('H:i') : '00:00' }}">
                        <div class="btn border" data-target="#total_duration" data-toggle="datetimepicker">
                            <i class="far fa-clock"></i>
                        </div>
                    </div>
                    <small class="text-danger" id="total_duration-error"></small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="temperature" class="form-label">Suhu</label>
                    <input type="text" placeholder="Suhu" name="temperature" class="form-control" id="temperature" value="{{ $usageLogbook->temperature }}">
                    <small class="text-danger" id="temperature-error"></small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="rh" class="form-label">RH</label>
                    <input type="text" placeholder="Rh" name="rh" class="form-control" id="rh" value="{{ $usageLogbook->rh }}">
                    <small class="text-danger" id="rh-error"></small>
                </div>
            </div>
            <div class="col-md-12">
                <div class="mb-3">
                    <label for="note" class="form-label">Catatan</label>
                    <textarea name="note" id="note" class="form-control" cols="15" rows="2">{{ old('note', $usageLogbook->note) }}</textarea>
                    <small class="text-danger" id="note-error"></small>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        var totalDurationValue = $('#total_duration').val();
        var dateValue = $('#date').val();

        // Format nilai untuk total duration
        var formattedTotalDuration = moment(totalDurationValue, 'HH:mm').isValid() ? moment(totalDurationValue, 'HH:mm') : moment('00:00', 'HH:mm');

        // Inisialisasi datetimepicker untuk total duration
        $('#total_duration').datetimepicker({
            format: 'HH:mm',
            defaultDate: formattedTotalDuration
        });

        // Format nilai untuk date_log
        var formattedDate = moment(dateValue, 'DD/MM/YYYY').isValid() ? moment(dateValue, 'DD/MM/YYYY') : moment();

        // Inisialisasi datetimepicker untuk date_log
        $('#date_log').datetimepicker({
            format: 'DD/MM/YYYY',
            defaultDate: formattedDate
        });

        $('.select2').select2({
            theme: 'bootstrap-5',
            placeholder: '--- Pilih Status ---',
            allowClear: true,
            dropdownParent: $("#modalAction")
        });

        $('#total_duration_input').on('input', function() {
            // Hapus semua karakter non-angka dari input
            var inputValue = this.value.replace(/[^0-9]/g, '');

            // Batasi panjang input hingga 4 angka (HHmm) + 1 titik dua
            if (inputValue.length > 4) {
                inputValue = inputValue.slice(0, 4);
            }

            // Tambahkan titik dua jika panjang input lebih dari 2 karakter
            if (inputValue.length > 2) {
                inputValue = inputValue.slice(0, 2) + ':' + inputValue.slice(2);
            }

            // Atur nilai input dengan hasil format yang telah diubah
            this.value = inputValue;
        });

        $('#date').on('input', function() {
            var inputValue = this.value;
            this.value = inputValue.replace(/[^0-9]/g, '');

            if (this.value.length > 2) {
                this.value = this.value.slice(0, 2) + '/' + this.value.slice(2, 4) + '/' + this.value.slice(4, 8);
            }

        });

        $('#temperature, #rh').on('input', function() {
            let inputValue = this.value;
            let cleanedValue = inputValue.replace(/[^0-9.]/g, '');

            if (inputValue !== cleanedValue) {
                this.value = cleanedValue;
                iziToast['warning']({
                    title: 'warning',
                    message: 'Input hanya boleh berupa angka.',
                    position: 'topCenter',
                    timeout: 3000,
                    progressBar: true,
                    displayMode: 'once',
                });
            }
        });
    })
</script>
