<?php

namespace App\Services;

use Exception;
use Carbon\Carbon;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\Facades\DataTables;

class ProductCategoryService
{
    public function dataTable()
    {
        $data = ProductCategory::select('*');
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('isActive', function ($row) {
                return $row->is_active ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
            })
            ->editColumn('name', function ($row) {
                return '<a href="' . route('admin.product-categories.products', $row->slug) . '" class="text-decoration-none">' . $row->name . '</a>';
            })
            ->editColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->setTimezone('Asia/Jakarta')->format('d-m-Y H:i:s');
            })
            ->editColumn('updated_at', function ($row) {
                return Carbon::parse($row->updated_at)->setTimezone('Asia/Jakarta')->format('d-m-Y H:i:s');
            })
            ->addColumn('action', function ($row) {
                $actionBtn = '';
                if (Gate::allows('update product-categories')) {
                    $actionBtn .= '<button type="button" name="edit" data-id="' . $row->id . '" class="editCategory btn btn-warning btn-sm me-2"><i class="ti-pencil-alt"></i></button>';
                }
                if (Gate::allows('delete product-categories')) {
                    $actionBtn .= '<button type="button" name="delete" data-id="' . $row->id . '" class="deleteCategory btn btn-danger btn-sm"><i class="ti-trash"></i></button>';
                }
                return '<div class="d-flex">' . $actionBtn . '</div>';
            })
            ->rawColumns(['action', 'isActive', 'name'])
            ->make(true);
    }

    public function getById($id)
    {
        return ProductCategory::findOrFail($id);
    }

    // store
    public function store(array $data)
    {
        try {

            // create category
            $category = ProductCategory::create($data);

            return [
                'success' => true,
                'message' => 'Data is saved successfully.',
                'category' => $category
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to save data: ' . $e->getMessage()
            ];
        }
    }


    // update category
    public function update($id, $data)
    {
        try {
            // check category
            $category = ProductCategory::findOrFail($id);

            // update category
            $category->update([
                'name' => $data['name'],
                'is_active' => $data['is_active'],
            ]);

            return [
                'success' => true,
                'message' => 'Data berhasil diperbarui.'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function destroy(ProductCategory $category)
    {
        try {
            // delete category
            $category->delete();

            return [
                'success' => true,
                'message' => 'The data was successfully deleted.'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to delete data: ' . $e->getMessage()
            ];
        }
    }
}
