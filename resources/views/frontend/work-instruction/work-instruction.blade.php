<div class="row mt-4">
    <div class="container">
        <ul class="nav nav-tabs flex-column flex-sm-row" id="ContentTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="content-tab" data-bs-toggle="tab" href="#content" role="tab" aria-controls="content" aria-selected="false">Deskripsi</a>
            </li>
        </ul>
        <div class="tab-content" id="ContentTabsContent">
            <div class="tab-pane fade show active" id="content" role="tabpanel" aria-labelledby="content-tab">
                <div class="mt-4 product-content">
                     {!! $product->content !!}
                </div>
            </div>
        </div>
        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs flex-column flex-sm-row" id="productDetailTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="instructions-tab" data-bs-toggle="tab" href="#instructions" role="tab" aria-controls="instructions" aria-selected="false">Instruksi Kerja</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="logbookUsage-tab" data-bs-toggle="tab" href="#logbookUsage" role="tab" aria-controls="logbookUsage" aria-selected="false">Logbook Penggunaan</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="logbookCalibration-tab" data-bs-toggle="tab" href="#logbookCalibration" role="tab" aria-controls="logbookCalibration" aria-selected="false">Logbook Kalibrasi</a>
            </li>
        </ul>

        <!-- Tabs Content -->
        <div class="tab-content" id="productDetailTabsContent">
            <div class="tab-pane fade show active" id="instructions" role="tabpanel" aria-labelledby="instructions-tab">
                <div class="mt-4">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive text-left">
                                <table class="table table-bordered dataTable">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Name</th>
                                            <th>isActive</th>
                                            <th>Created</th>
                                            <th>Updated</th>
                                            <th width="100px">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="logbookUsage" role="tabpanel" aria-labelledby="logbookUsage-tab">
                <div class="mt-4">
                    <!-- Content for Logbook Penggunaan -->
                </div>
            </div>
            <div class="tab-pane fade" id="logbookCalibration" role="tabpanel" aria-labelledby="logbookCalibration-tab">
                <div class="mt-4">
                    <!-- Content for Logbook Kalibrasi -->
                </div>
            </div>
        </div>
    </div>
</div>
