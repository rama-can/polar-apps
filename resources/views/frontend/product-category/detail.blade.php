<x-front-layout :title="$title">
    @push('styles')
    <style>
        .card {
            margin-bottom: 1rem;
        }
        .card-img-top {
            width: 100%;
            height: 200px; /* Adjust height as needed */
            object-fit: cover;
        }
        .card-body {
            display: flex;
            flex-direction: column;
            flex: 1 1 auto;
        }
        .card-title {
            font-size: 1rem; /* Adjust font size as needed */
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>
    @endpush
    <div class="container">
        <h1 class="my-4">{{ $category->name }}</h1>
        <div class="row">
            <div class="col-12">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center align-items-md-center mt-4">
                    <x-button-back></x-button-back>
                    <form method="GET" action="{{ route('products.index') }}" class="mb-4">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Cari..." value="{{ request('search') }}">
                            <button class="btn btn-primary" type="submit">Cari</button>
                        </div>
                    </form>
                </div>
                @if($products->count() > 0)
                    <p>Menampilkan {{ $products->firstItem() }} hingga {{ $products->lastItem() }} dari {{ $products->total() }} hasil</p>
                @endif
                <div class="row">
                    <!-- Repeat this block for each product in the category -->
                    @foreach ($products as $product)
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="card mb-4">
                            <div class="img-container">
                                <img src="{{ $product->images->first() ? asset($product->images->first()->url) : 'https://via.placeholder.com/150' }}" class="card-img-top" alt="{{ $product->name }}">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">{{ $product->name }}</h5>
                                <a href="{{ route('products.detail', ['category' => $category->slug, 'product' => $product->slug]) }}" class="btn btn-primary">Baca Selengkapnya</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    <!-- End of product block -->
                </div>
                <div class="justify-content-center">
                    {{ $products->links('pagination::bootstrap-5') }}
                </div>
            </div>
            <!-- End of category section -->
        </div>
    </div>
</x-front-layout>
