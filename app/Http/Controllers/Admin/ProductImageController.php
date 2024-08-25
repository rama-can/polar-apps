<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class ProductImageController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read product-images')->only(['index', 'show']);
        $this->middleware('permission:create product-images')->only(['create', 'store']);
        $this->middleware('permission:update product-images')->only(['edit', 'update']);
        $this->middleware('permission:delete product-images')->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = ProductImage::where('product_id', $request->product_id)->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->setTimezone('Asia/Jakarta')->format('d-m-Y');
                })
                ->editColumn('preview', function ($row) {
                    return '<a href="'. $row->url .'" target="__BLANK">Link</a>';
                })
                ->addColumn('action', function ($row) {
                    $actionBtn = '';
                    if (Gate::allows('update product-images')) {
                        $actionBtn = '<button type="button" name="edit" data-id="' . $row->id . '" class="editImage btn btn-warning btn-sm me-2"><i class="ti-pencil-alt"></i></button>';
                    }
                    if (Gate::allows('delete product-images')) {
                        $actionBtn .= '<button type="button" name="delete" data-id="' . $row->id . '" class="deleteImage btn btn-danger btn-sm"><i class="ti-trash"></i></button>';
                    }
                    return '<div class="d-flex">' . $actionBtn . '</div>';
                })
                ->rawColumns(['action', 'preview'])
                ->make(true);
        }

        return response()->json(['message' => 'success']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $productImage = new ProductImage();

        return view('admin.product.form-image', compact('product', 'productImage'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);
        // dd($request->all());

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $path = $request->file('image')->store('images/products', 'public');

        // Mendapatkan URL lengkap gambar
        $url = Storage::url($path);

        // Simpan data ke database
        $image = ProductImage::create([
            'product_id' => $request->product_id,
            'url' => url($url) // Menghasilkan URL lengkap
        ]);


        return response()->json(['message' => 'Image created successfully!', 'data' => $image]);
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
        $productImage = ProductImage::find($id);

        if (!$productImage) {
            return response()->json(['message' => 'Image not found!'], 404);
        }

        $product = Product::find($productImage->product_id);

        return view('admin.product.form-image', compact('productImage', 'product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Temukan image berdasarkan ID
        $productImage = ProductImage::findOrFail($id);

        if (!$productImage) {
            return response()->json(['message' => 'Image not found!'], 404);
        }

        // Proses upload file gambar
        if ($request->hasFile('image')) {
            // Hapus gambar lama dari storage
            if ($productImage->url) {
                $pathDelete = str_replace(url('/storage/'), '', $productImage->url);
                if (Storage::disk('public')->exists($pathDelete)) {
                    Storage::disk('public')->delete($pathDelete);
                }
            }

            // Simpan gambar baru
            $file = $request->file('image');
            $filePath = $file->store('images/products', 'public');
            $path = Storage::url($filePath);

            // Update data image di database
            $productImage->url = url($path);
        }

        $productImage->product_id = $request->product_id;
        $productImage->save();

        return response()->json(['message' => 'Image updated successfully!', 'data' => $productImage]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $image = ProductImage::find($id);
        try {
            if (!$image) {
                return response()->json(['message' => 'Image not found'], 404);
            }

            // Hapus file gambar dari storage jika ada
            if ($image->url) {
                $pathDelete = str_replace(url('/storage/'), '', $image->url);
                if (Storage::disk('public')->exists($pathDelete)) {
                    Storage::disk('public')->delete($pathDelete);
                }
            }
            // delete image
            $image->delete();

            return [
                'success' => true,
                'message' => 'Image deleted successfully'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to delete data: ' . $e->getMessage()
            ];
        }
    }
}
