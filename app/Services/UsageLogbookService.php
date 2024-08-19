<?php

namespace App\Services;

use Exception;
use App\Models\Product;
use App\Models\UsageLogbook;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\Facades\DataTables;

class UsageLogbookService
{
    public function dataTable(string $product)
    {
        $usageLogbook = UsageLogbook::where('product_id', $product)
        ->with(['product'])
        ->get();

        return DataTables::of($usageLogbook)
            ->addIndexColumn()
            ->editColumn('date', function ($usageLogbook) {
                return Carbon::parse($usageLogbook->date)->format('d-m-Y');
            })
            ->editColumn('total_duration', function ($usageLogbook) {
                return Carbon::parse($usageLogbook->total_duration)->format('H:i');
            })
            ->editColumn('temperature', function ($usageLogbook) {
                return $usageLogbook->temperature . ' °C';
            })
            ->editColumn('status', function ($row) {
                switch ($row->status) {
                    case 'MAHASISWA':
                        return '<span class="badge bg-primary">Mahasiswa</span>';
                    case 'PLP':
                        return '<span class="badge bg-success">PLP</span>';
                    case 'DOSEN':
                        return '<span class="badge bg-info">Dosen</span>';
                    case 'PENELITI':
                        return '<span class="badge bg-warning">Peneliti</span>';
                    default:
                        return '<span class="badge bg-secondary">Lainnya</span>';
                }
            })
            ->addColumn('action', function ($row) {
                $actionBtn = '';
                if (Gate::allows('update usage-logbooks')) {
                    $actionBtn .= '<button type="button" name="edit" data-id="' . $row->id . '" class="editLogbook btn btn-warning btn-sm me-2"><i class="ti-pencil-alt"></i></button>';
                }
                if (Gate::allows('delete usage-logbooks')) {
                    $actionBtn .= '<button type="button" name="delete" data-id="' . $row->id . '" class="deleteLogbook btn btn-danger btn-sm"><i class="ti-trash"></i></button>';
                }
                return $actionBtn ;
            })
            ->rawColumns(['action', 'status'])
            ->make(true);

    }

    public function getById($id)
    {
        return UsageLogbook::findOrFail($id);
    }

    // create
    public function create(array $data, string $product)
    {
        try {

            $data['product_id'] = $product;
            $data['date'] = Carbon::createFromFormat('d/m/Y', $data['date_log'])->format('Y-m-d');
            $data['total_duration'] = Carbon::parse($data['total_duration'])->format('H:i');

            $usageLogbook = UsageLogbook::create($data);

            return [
                'success' => true,
                'message' => 'Data is saved successfully.',
                'usa$usageLogbook' => $usageLogbook
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
            // check logbook
            $logbook = UsageLogbook::findOrFail($id);

            // update logbook
            $logbook->update([
                'date' => Carbon::createFromFormat('d/m/Y', $data['date_log'])->format('Y-m-d'),
                'name' => $data['name'],
                'status' => $data['status'],
                'total_duration' => Carbon::parse($data['total_duration'])->format('H:i'),
                'temperature' => $data['temperature'],
                'rh' => $data['rh'],
                'note' => $data['note'],
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

    public function destroy(UsageLogbook $usageLogbook)
    {
        try {
            // delete usageLogbook
            $usageLogbook->delete();

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
