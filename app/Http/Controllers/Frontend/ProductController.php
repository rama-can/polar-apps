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
            ->where('status', true)
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', '%' . strtolower($search) . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('frontend.product.index', [
            'title' => 'Semua Peralatan Laboratorium',
            'products' => $products
        ]);
    }

    public function detail(string $category, string $product)
    {
        $category = ProductCategory::where('slug', $category)
            ->where('is_active', true)
            ->firstOrFail();
            
        $product = Product::where('slug', $product)
            ->where('status', true)
            ->whereHas('category', function ($query) use ($category) {
            $query->where('id', $category->id);
        })
        ->firstOrFail();

        $product->hashId = $this->hashId->encode($product->id);

        return view('frontend.product.detail', [
            'title' => $product->name,
            'category' => $category,
            'product' => $product
        ]);
    }
}
