<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Product;
use App\Models\UsageLogbook;
use Illuminate\Http\Request;
use App\Services\HashIdService;
use App\Http\Controllers\Controller;
use App\Http\Requests\UsageLogbookRequest;
use App\Services\UsageLogbookService;

class UsageLogbookController extends Controller
{
    protected $usageLogbook;
    protected $hashId;

    public function __construct(
        UsageLogbookService $usageLogbook,
        HashIdService $hashId
    ) {
        $this->middleware('permission:read front-usage-logbooks')->only(['index']);
        $this->middleware('permission:create front-usage-logbooks')->only(['create', 'store']);
        $this->middleware('permission:update front-usage-logbooks')->only(['edit', 'update']);
        $this->middleware('permission:delete front-usage-logbooks')->only(['destroy']);
        $this->usageLogbook = $usageLogbook;
        $this->hashId = $hashId;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(string $product, Request $request)
    {
        $hashId = $product;
        $productId = $this->hashId->decode($product);

        $product = Product::where('id', $productId)->firstOrFail();

        if(!$product){
            abort(404);
        }

        if ($request->ajax()) {
            return $this->usageLogbook->dataTable($productId);
        }

        return view('frontend.usage-logbook.index', [
            'title' => 'Logbook Penggunaan Instrument',
            'product' => $product,
            'hashId' => $hashId,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(string $product)
    {
        // dd('create');
        $usageLogbook = new UsageLogbook();
        $productId = $this->hashId->decode($product);

        $product = Product::where('id', $productId)->firstOrFail();

        if(!$product){
            abort(404);
        }

        return view('frontend.usage-logbook.form', [
            'title' => 'Tambah Logbook Penggunaan Instrument',
            'product' => $product,
            'usageLogbook' => $usageLogbook,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UsageLogbookRequest $request, string $product)
    {
        $productId = $this->hashId->decode($product);
        $product = Product::where('id', $productId)->firstOrFail();
        if(!$product){
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ]);
        }

        $result = $this->usageLogbook->create($request->all(), $product->id);
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
    public function edit(string $product, string $logbook)
    {
        $productId = $this->hashId->decode($product);
        $product = Product::where('id', $productId)->firstOrFail();
        $usageLogbook = $this->usageLogbook->getById($logbook);

        if(!$product){
            abort(404);
        }

        return view('frontend.usage-logbook.form', [
            'title' => 'Edit Logbook Penggunaan Instrument',
            'product' => $product,
            'usageLogbook' => $usageLogbook,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UsageLogbookRequest $request, string $product, string $id,)
    {
        $productId = $this->hashId->decode($product);
        $product = Product::where('id', $productId)->firstOrFail();
        if(!$product){
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ]);
        }
        $result = $this->usageLogbook->update($id, $request->all());
        return response()->json($result);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $product, UsageLogbook $usageLogbook)
    {
        $productId = $this->hashId->decode($product);
        $product = Product::where('id', $productId)->firstOrFail();
        if(!$product){
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ]);
        }
        $result = $this->usageLogbook->destroy($usageLogbook);
        return response()->json($result);
    }
}
