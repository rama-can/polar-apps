<?php

namespace App\Http\Controllers\Frontend;

use App\Models\ProductCategory;
use App\Http\Controllers\Controller;

class ProductCategoryController extends Controller
{
    public function index()
    {
        return view('frontend.product-category.index', [
            'title' => 'Kategori Peralatan Laboratorium',
            'categories' => ProductCategory::where('is_active', true)->get()
        ]);
    }

    public function detail($slug)
    {
        $category = ProductCategory::where('slug', $slug)
                        ->where('is_active', true)
                        ->firstOrFail();

        $products = $category->products()->where('status', true)
                        ->with('category')
                        ->orderBy('created_at', 'desc')
                        ->paginate(8);

        return view('frontend.product-category.detail', [
            'title' => 'Peralatan Laboratorium Kategori', $category->name,
            'category' => $category,
            'products' => $products
        ]);
    }
}
