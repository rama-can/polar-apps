<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\ProductCategory;
use GuzzleHttp\Psr7\Response;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Js;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->middleware('permission:read products')->only(['index']);
        $this->middleware('permission:create products')->only(['create', 'store']);
        $this->middleware('permission:update products')->only(['edit', 'update']);
        $this->middleware('permission:delete products')->only(['destroy']);
        $this->productService = $productService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response|View|JsonResponse
    {

        $title = 'Products';
        if ($request->ajax()) {
            return $this->productService->datatable();
        }

        return view('admin.product.index', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Add Product';
        $categories = ProductCategory::all();

        return view('admin.product.create', compact('title', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        $result = $this->productService->store($request->all());

        if ($result['success']) {
            return redirect()->route('admin.products.edit', $result)->with('success', $result['message']);
        } else {
            return back()->withInput()->with('error', $result['message']);
        }
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
        $title = 'Edit Product';
        $categories = ProductCategory::all();
        $product = $this->productService->getById($id);

        return view('admin.product.edit', compact('title', 'product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, string $id)
    {
        $result = $this->productService->update($request->all(), $id);

        if ($result['success']) {
            return redirect()->route('admin.products.index')->with('success', $result['message']);
        } else {
            return back()->withInput()->with('error', $result['message']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $result = $this->productService->delete($id);

        return response()->json($result);
    }

    // /**
    //  * Retrieve the products for a specific category.
    //  *
    //  * @param string $id The ID of the category.
    //  * @return void
    //  */
    // public function showByCategory(Request $request, string $id)
    // {
    //     $category = ProductCategory::where('slug', $id)->first();
    //     $title = 'Products in ' . $category->name;
    //     if ($request->ajax()) {
    //         return $this->productService->datatable($category->id);
    //     }

    //     return view('admin.product.index', compact('title'));
    // }
}
