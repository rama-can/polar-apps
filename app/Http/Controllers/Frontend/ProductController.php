<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Services\HashIdService;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    protected $hashId;
    public function __construct(HashIdService $hashId)
    {
        $this->hashId = $hashId;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');

        if ($search && strlen($search) < 3) {
            return redirect()->back()->with('warning', 'Search must be at least 3 characters.');
        }

        $products = Product::query()
            ->with('category')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', '%' . strtolower($search) . '%');
            })
            ->paginate(12);

        return view('frontend.product.index', [
            'title' => 'Semua Peralatan Laboratorium',
            'products' => $products
        ]);
    }

    public function detail(string $category, string $product)
    {
        $product = Product::where('slug', $product)->firstOrFail();
        $category = ProductCategory::where('slug', $category)->firstOrFail();

        $product->hashId = $this->hashId->encode($product->id);

        return view('frontend.product.detail', [
            'title' => $product->name,
            'category' => $category,
            'product' => $product
        ]);
    }
}
