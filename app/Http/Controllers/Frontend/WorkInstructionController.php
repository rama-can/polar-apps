<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\WorkInstruction;
use App\Services\HashIdService;
use App\Http\Controllers\Controller;
use App\Services\WorkInstructionService;
use App\Http\Requests\WorkInstructionRequest;

class WorkInstructionController extends Controller
{
    protected $workIns;
    protected $hashId;
    public function __construct(WorkInstructionService $workIns, HashIdService $hashId)
    {
        $this->middleware('permission:read front-work-instructions')->only(['index']);
        $this->middleware('permission:create front-work-instructions')->only(['create', 'store']);
        $this->middleware('permission:update front-work-instructions')->only(['edit', 'update']);
        $this->middleware('permission:delete front-work-instructions')->only(['destroy']);
        $this->workIns = $workIns;
        $this->hashId = $hashId;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(string $product)
    {
        $productId = $this->hashId->decode($product);
        $product = Product::where('id', $productId)->firstOrFail();

        if(!$product){
            abort(404);
        }

        $workInstruction = WorkInstruction::where('product_id', $productId)->first();

        return view('frontend.work-instruction.index', [
            'title' => 'Intruksi Kerja',
            'product' => $product,
            'workInstruction' => $workInstruction
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(string $product)
    {
        $product = Product::where('id', $product)->firstOrFail();
        if(!$product){
            abort(404);
        }

        $workIns = new WorkInstruction();
        return view('frontend.work-instruction.form', compact('workIns', 'product'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(WorkInstructionRequest $request)
    {
        $file = $request->file('file');
        if (!$file) {
            return response()->json([
                'status' => false,
                'message' => 'File tidak boleh kosong'
            ], 422);
        }
        $result = $this->workIns->store($request->all());
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
    public function edit(string $product, string $workInstruction)
    {
        $product = Product::where('id', $product)->firstOrFail();

        if(!$product){
            abort(404);
        }

        $workIns = WorkInstruction::where('id', $workInstruction)->firstOrFail();
        return view('frontend.work-instruction.form', compact('workIns', 'product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(WorkInstructionRequest $request, string $product, string $workInstruction)
    {
        $file = $request->file('file');
        if (!$file) {
            return response()->json([
                'status' => false,
                'message' => 'File tidak boleh kosong'
            ], 422);
        }

        $result = $this->workIns->update($workInstruction, $request->all());

        return response()->json($result);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
