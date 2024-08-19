<x-front-layout :title="$title">
    <div class="container mt-5">
        <h1 class="text-center">{{ $title ?? '' }}</h1>
        <div class="row mt-5">
            <!-- Category Card Example -->
            @foreach ($categories as $category)
            <div class="col-12 col-md-6 mb-4 text-center">
                <a href="{{ route('product-category.detail', $category->slug) }}" class="btn btn-dark w-50 text-bg-light">{{ $category->name }}</a>
            </div>
            @endforeach
            <!-- Add more category cards as needed -->
        </div>
    </div>
</x-front-layout>
