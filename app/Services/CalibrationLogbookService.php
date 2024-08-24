<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Carbon;
use App\Models\CalibrationLogbook;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class CalibrationLogbookService
{
    public function dataTable(string $product)
    {
        $data = CalibrationLogbook::where('product_id', $product)
            ->orderBy('created_at', 'desc')
            ->with(['product'])
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('date', function ($row) {
                return Carbon::parse($row->date)->format('d-m-Y');
            })
            ->editColumn('document', function ($row) {
                return '<button data-id="' . $row->document . '" class="badge bg-secondary previewDocument">View</button>';
            })
            ->addColumn('action', function ($row) {
                $actionBtn = '';
                if (Gate::allows('update calibration-logbooks') || Gate::allows('update front-calibration-logbooks')) {
                    $actionBtn .= '<button type="button" name="edit" data-id="' . $row->id . '" class="editLogbook btn btn-warning btn-sm me-2"><i class="ti-pencil-alt"></i></button>';
                }
                if (Gate::allows('delete calibration-logbooks') || Gate::allows('delete front-calibration-logbooks')) {
                    $actionBtn .= '<button type="button" name="delete" data-id="' . $row->id . '" class="deleteLogbook btn btn-danger btn-sm"><i class="ti-trash"></i></button>';
                }
                return $actionBtn ;
            })
            ->rawColumns(['action', 'document'])
            ->make(true);
    }

    public function getById($id)
    {
        return CalibrationLogbook::findOrFail($id);
    }

    // create
    public function create(array $data, string $product)
    {
        try {

            $data['product_id'] = $product;
            $data['date'] = Carbon::createFromFormat('d/m/Y', $data['date_log'])->format('Y-m-d');

            if(isset($data['document']) && $data['document']->isValid()){
                // Get the original file extension
                $extension = $data['document']->getClientOriginalExtension();

                // Create a custom file name
                $fileName = 'calibration-logbook-' . time() . '.' . $extension;

                // Store the file with the custom file name
                $path = $data['document']->storeAs('images/calibration-logbook', $fileName, 'public');

                // Get the file URL
                $url = Storage::url($path);
            }

            $data['document'] = $url;

            $calibrationLogBook = CalibrationLogbook::create($data);

            return [
                'success' => true,
                'message' => 'Data is saved successfully.',
                'calibrationLogBook' => $calibrationLogBook
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to save data: ' . $e->getMessage()
            ];
            Log::error($e->getMessage());
        }
    }


    // update category
    public function update($id, $data)
    {
        try {
            // check logbook
            $logbook = CalibrationLogbook::findOrFail($id);

            $oldFile = $logbook->document;
            if (isset($data['document']) && $data['document']->isValid()) {
                // delete old file
                $path = str_replace('/storage/', '', $oldFile);
                if (!empty($oldFile) && Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }

                // Get the original file extension
                $extension = $data['document']->getClientOriginalExtension();

                // Create a custom file name
                $fileName = 'calibration-logbook-' . time() . '.' . $extension;

                // Store the file with the custom file name
                $path = $data['document']->storeAs('images/calibration-logbook', $fileName, 'public');

                // Get the file URL
                $url = Storage::url($path);
                $data['document'] = $url;
            } else {
                $data['document'] = $oldFile;
            }

            // update logbook
            $logbook->update([
                'date' => Carbon::createFromFormat('d/m/Y', $data['date_log'])->format('Y-m-d'),
                'technician' => $data['technician'],
                'institution' => $data['institution'],
                'document' => $data['document'],
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

    public function destroy(CalibrationLogbook $calibrationLogbook)
    {
        try {
            // deleteCalibrationLogbook
            $oldFile = $calibrationLogbook->document;
            $path = str_replace('/storage/', '', $oldFile);
            if (!empty($oldFile) && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            $calibrationLogbook->delete();

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
