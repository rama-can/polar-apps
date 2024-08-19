<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductCategoryRequest;
use App\Models\ProductCategory;
use App\Services\ProductCategoryService;

class ProductCategoryController extends Controller
{
    protected $productCategoryService;

    public function __construct(ProductCategoryService $productCategoryService)
    {
        $this->middleware('permission:read product-categories');
        $this->middleware('permission:create product-categories')->only(['create', 'store']);
        $this->middleware('permission:update product-categories')->only(['edit', 'update']);
        $this->middleware('permission:delete product-categories')->only(['destroy']);
        $this->productCategoryService = $productCategoryService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = 'Product Category';
        if ($request->ajax()) {
            return $this->productCategoryService->dataTable();
        }
        return view('admin.product-category.index', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $productCategory = new ProductCategory();

        return view('admin.product-category.form', compact('productCategory'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductCategoryRequest $request)
    {
        $result = $this->productCategoryService->store($request->all());
        return response()->json($result);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $title = 'Edit Product Category';
        $productCategory = $this->productCategoryService->getById($id);

        return view('admin.product-category.form', compact('title', 'productCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductCategoryRequest $request, string $id)
    {
        $result = $this->productCategoryService->update($id, $request->all());
        return response()->json($result);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductCategory $productCategory)
    {
        $result = $this->productCategoryService->destroy($productCategory);
        return response()->json($result);
    }
}
