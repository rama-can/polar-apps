<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\HashIdService;
use App\Http\Controllers\Controller;
use App\Http\Requests\CalibrationLogbookRequest;
use App\Models\CalibrationLogbook;
use App\Services\CalibrationLogbookService;

class CalibrationLogbookController extends Controller
{
    protected $calibrationLogbook;
    protected $hashId;

    public function __construct(
        CalibrationLogbookService $calibrationLogbook,
        HashIdService $hashId
    ) {
        $this->middleware('permission:read front-calibration-logbooks')->only(['index']);
        $this->middleware('permission:create front-calibration-logbooks')->only(['create', 'store']);
        $this->middleware('permission:update front-calibration-logbooks')->only(['edit', 'update']);
        $this->middleware('permission:delete front-calibration-logbooks')->only(['destroy']);
        $this->calibrationLogbook = $calibrationLogbook;
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
            return $this->calibrationLogbook->dataTable($productId);
        }

        return view('frontend.calibration-logbook.index', [
            'title' => 'Logbook Kalibrasi Instrument',
            'product' => $product,
            'hashId' => $hashId,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(string $product)
    {
        $calLogBook = new CalibrationLogbook();
        $productId = $this->hashId->decode($product);

        $product = Product::where('id', $productId)->firstOrFail();

        if(!$product){
            abort(404);
        }

        return view('frontend.calibration-logbook.form', [
            'title' => 'Tambah Logbook Penggunaan Instrument',
            'product' => $product,
            'calLogBook' => $calLogBook,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CalibrationLogbookRequest $request, string $product)
    {
        $productId = $this->hashId->decode($product);
        $product = Product::where('id', $productId)->firstOrFail();
        // dd($product);
        if(!$product){
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ]);
        }

        $response = $this->calibrationLogbook->create($request->all(), $product->id);

        return response()->json($response);
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
    public function edit(string $product, string $id)
    {
        $productId = $this->hashId->decode($product);
        $product = Product::where('id', $productId)->firstOrFail();
        $calLogBook = $this->calibrationLogbook->getById($id);

        if(!$product && !$calLogBook){
            abort(404);
        }

        return view('frontend.calibration-logbook.form', [
            'title' => 'Edit Logbook Kalibrasi Instrument',
            'product' => $product,
            'calLogBook' => $calLogBook,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CalibrationLogbookRequest $request,string $product, string $id)
    {
        $productId = $this->hashId->decode($product);
        $product = Product::where('id', $productId)->firstOrFail();
        if(!$product){
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ]);
        }

        $response = $this->calibrationLogbook->update($id, $request->all());

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $product, CalibrationLogbook $calibrationLogbook)
    {
        $productId = $this->hashId->decode($product);
        $product = Product::where('id', $productId)->firstOrFail();
        if(!$product){
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ]);
        }

        $response = $this->calibrationLogbook->destroy($calibrationLogbook);

        return response()->json($response);
    }
}
