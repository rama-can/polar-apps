<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CalibrationLogbookRequest;
use App\Models\CalibrationLogbook;
use App\Services\CalibrationLogbookService;

class CalibrationLogbookController extends Controller
{
    protected $calibrationLogbook;

    public function __construct(
        CalibrationLogbookService $calibrationLogbook,
    ) {
        $this->middleware('permission:read calibration-logbooks')->only(['index']);
        $this->middleware('permission:create calibration-logbooks')->only(['create', 'store']);
        $this->middleware('permission:update calibration-logbooks')->only(['edit', 'update']);
        $this->middleware('permission:delete calibration-logbooks')->only(['destroy']);
        $this->calibrationLogbook = $calibrationLogbook;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(string $product, Request $request)
    {
        $product = Product::where('id', $product)->firstOrFail();

        if(!$product){
            abort(404);
        }

        if ($request->ajax()) {
            return $this->calibrationLogbook->dataTable($product->id);
        }

        return view('admin.calibration-logbook.index', [
            'title' => 'Logbook Kalibrasi Instrument',
            'product' => $product,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(string $product)
    {
        $calLogBook = new CalibrationLogbook();

        $product = Product::where('id', $product)->firstOrFail();

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
        $product = Product::where('id', $product)->firstOrFail();

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
        $product = Product::where('id', $product)->firstOrFail();
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
        $product = Product::where('id', $product)->firstOrFail();
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
        $product = Product::where('id', $product)->firstOrFail();
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
