<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ProductService
{
    public function dataTable()
    {
        $data = Product::with('category')->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('status', function ($row) {
                return $row->status ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
            })
            ->addColumn('category', function ($row) {
                return $row->category->name;
            })
            ->editColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->setTimezone('Asia/Jakarta')->format('d-m-Y');
            })
            ->addColumn('modules', function ($row) {
                if (Gate::allows('read usage-logbooks') && Gate::allows('read calibration-logbooks')) {
                    return '
                        <div class="d-flex flex-wrap justify-content-start">
                            <a href="' . route('admin.usage-logbooks.index', $row->id) . '" class="btn btn-primary btn-sm d-flex align-items-center me-2 mb-2">
                                <i class="fa-solid fa-book me-1"></i> Usages
                            </a>
                            <a href="' . route('admin.calibration-logbooks.index', $row->id) . '" class="btn btn-primary btn-sm d-flex align-items-center me-2 mb-2">
                                <i class="fa-solid fa-book me-1"></i> Calibrations
                            </a>
                            <a href="' . route('admin.work-instructions.index', $row->id) . '" class="btn btn-primary btn-sm d-flex align-items-center mb-2">
                                <i class="fa-solid fa-book me-1"></i> Work Instruction
                            </a>
                        </div>';
                }
            })

            ->addColumn('QRcode', function ($row) {
                if (Gate::allows('read qrcodes')) {
                return '<div class="d-flex align-items-center">
                            <button type="button" name="showQrCodeProduct" data-id="' . $row->id . '" class="showQrCodeProduct btn btn-info btn-sm me-2">
                                <i class="ti-eye"></i>
                            </button>
                        </div>';
                        // <a href="' . route('admin.qrcode.download', $row->id) . '" class="btn btn-success btn-sm" download>
                        //         <i class="ti-download"></i>
                        //     </a>
                }
            })
            ->addColumn('action', function ($row) {
                $actionBtn = '';

                if (Gate::allows('update products')) {
                    $actionBtn .= '<a href="' . route('admin.products.edit', $row->id) . '" name="edit" data-id="' . $row->id . '" class="editRole btn btn-warning btn-sm me-2">
                                        <i class="ti-pencil-alt"></i>
                                   </a>';
                }

                if (Gate::allows('delete products')) {
                    $actionBtn .= '<button type="button" name="delete" data-id="' . $row->id . '" class="deleteProduct btn btn-danger btn-sm">
                                        <i class="ti-trash"></i>
                                   </button>';
                }

                return '<div class="d-flex align-items-center">' . $actionBtn . '</div>';
            })
            ->rawColumns(['action', 'status', 'category', 'QRcode', 'modules'])
            ->make(true);
    }

    public function getById($id)
    {
        return Product::findOrFail($id);
    }

    public function store(array $data)
    {
        DB::beginTransaction();

        try {
            $product = Product::create($data);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Data is saved successfully.',
                'product' => $product
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => 'Failed to save data: ' . $e->getMessage()
            ];
        }
    }

    public function update(array $data, string $id)
    {
        DB::beginTransaction();

        try {
            // find product
            $product = Product::findOrFail($id);
            $product->update($data);

            DB::commit();

            return [
                'success' => true,
                'message' => 'The data was successfully changed.',
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => 'Failed to change the data: ' . $e->getMessage()
            ];
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();

        try {
            // find product
            $product = Product::find($id);

            if ($product) {

                // delete product & images
                $productImages = ProductImage::where('product_id', $product->id)->get();

                // Delete the product images and their files
                foreach ($productImages as $image) {
                    if ($image->url) {
                        $pathDelete = str_replace(url('/storage/'), '', $image->url);
                        if (Storage::disk('public')->exists($pathDelete)) {
                            Storage::disk('public')->delete($pathDelete);
                        }
                    }
                    $image->delete();
                }

                // Delete the product
                $product->delete();

                DB::commit();

                return [
                    'success' => true,
                    'message' => 'The data was successfully deleted.',
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Data not found.',
                ];
            }
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => 'Failed to delete data: ' . $e->getMessage(),
            ];
        }
    }
}
