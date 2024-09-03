<x-front-layout :title="$title">
    @push('styles')
    <style>
        .carousel-item img {
            height: 400px;
            object-fit: cover;
        }
        .cke_contents {
            /* Custom styles for CKEditor content */
            max-width: 100%;
        }

        .cke_content img {
            max-width: 100%;
            height: auto;
        }

        .cke_content table {
            width: 100%;
            overflow-x: auto;
        }
        .product-content {
            margin-left: 25px; /* Adjust the margin as needed */
        }
    </style>
    @endpush

    <div class="container-fluid my-3 border rounded p-4">
        <div class="row">
            <div class="col-md-6">
                <x-button-back></x-button-back>
                <!-- Carousel -->
                <div id="photoCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <!-- Repeat this block for each image -->
                        @foreach ($product->images as $index => $image)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <img src="{{ $image->url }}" class="d-block w-100 border rounded" alt="Product Image" data-bs-toggle="modal" data-bs-target="#imageModal" data-bs-image="{{ $image->url }}">
                            </div>
                        @endforeach
                        <!-- End of image block -->
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#photoCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon text-bg-success" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#photoCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon text-bg-success" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="imageModalLabel">Image Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <img id="modalImage" src="" class="img-fluid" alt="Product Image">
                            </div>
                            <div class="modal-footer">
                                <a id="downloadButton" href="#" class="btn btn-primary" download="">Download</a>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-6">
                <!-- Product Information -->
                <h1 class="mt-4">{{ $product->name }}</h1>
                <div class="fw-normal">
                    {!! $product->description !!}
                </div>
                <div class="mt-4 fw-semibold d-flex align-items-center">
                    <span>Kategori:</span>
                    <a href="{{ route('product-category.detail', $category->slug) }}" class="btn btn-danger btn-sm ms-2">
                        {{ $category->name }}
                    </a>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="container">
                <ul class="nav nav-tabs flex-column flex-sm-row" id="ContentTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="content-tab" data-bs-toggle="tab" href="#content" role="tab" aria-controls="content" aria-selected="false">Deskripsi</a>
                    </li>
                </ul>
                <div class="tab-content" id="ContentTabsContent">
                    <div class="tab-pane fade show active" id="content" role="tabpanel" aria-labelledby="content-tab">
                        <div class="mt-4 product-content col-10 fw-normal">
                            {!! $product->content !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container border-top">
            <div class="row mt-5 justify-content-center">
                <div class="col-12 col-md-6 col-lg-3 mb-4 text-center">
                    <a href="{{ route('work-instructions.index', $product->hashId) }}" class="btn btn-info w-100 text-bg-light">Instruksi Kerja</a>
                </div>
                <div class="col-12 col-md-6 col-lg-3 mb-4 text-center">
                    <a href="{{ route('usage-logbooks.index', $product->hashId) }}" class="btn btn-info w-100 text-bg-light">Logbook Penggunaan</a>
                </div>
                <div class="col-12 col-md-6 col-lg-3 mb-4 text-center">
                    <a href="{{ route('calibration-logbooks.index', $product->hashId) }}" class="btn btn-info w-100 text-bg-light">Logbook Kalibrasi</a>
                </div>
            </div>
        </div>
    </div>

    @push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var carouselImages = document.querySelectorAll('#photoCarousel .carousel-item img');
            var modalImage = document.getElementById('modalImage');
            var downloadButton = document.getElementById('downloadButton');

            carouselImages.forEach(function(image) {
                image.addEventListener('click', function() {
                    var imageUrl = this.getAttribute('data-bs-image');
                    modalImage.src = imageUrl;
                    var fileName = imageUrl.split('/').pop();
                    downloadButton.href = imageUrl;
                    downloadButton.setAttribute('download', fileName);
                });
            });
        });
    </script>

    @endpush
</x-front-layout>
