<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Carbon;
use App\Models\WorkInstruction;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class WorkInstructionService
{
    protected $hashIdService;

    public function __construct(HashIdService $hashIdService)
    {
        $this->hashIdService = $hashIdService;
    }

    public function dataTable()
    {
        $data = WorkInstruction::select('*');
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('isActive', function ($row) {
                return $row->is_active ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
            })
            ->editColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->setTimezone('Asia/Jakarta')->format('d-m-Y H:i:s');
            })
            ->editColumn('updated_at', function ($row) {
                return Carbon::parse($row->updated_at)->setTimezone('Asia/Jakarta')->format('d-m-Y H:i:s');
            })
            ->addColumn('action', function ($row) {
                $actionBtn = '';
                if (Gate::allows('update work-instructions')) {
                    $actionBtn .= '<button type="button" name="edit" data-id="' . $row->id . '" class="editworkInstruction btn btn-warning btn-sm me-2"><i class="ti-pencil-alt"></i></button>';
                }
                if (Gate::allows('delete work-instructions')) {
                    $actionBtn .= '<button type="button" name="delete" data-id="' . $row->id . '" class="deleteworkInstruction btn btn-danger btn-sm"><i class="ti-trash"></i></button>';
                }
                return '<div class="d-flex">' . $actionBtn . '</div>';
            })
            ->rawColumns(['action', 'isActive'])
            ->make(true);
    }

    public function getById($id)
    {
        // $hash = $this->hashIdService->decode($id);
        return WorkInstruction::findOrFail($id);
    }

    // store
    public function store(array $data)
    {
        try {
            if (isset($data['file']) && $data['file']->isValid()) {
                // Simpan file
                $path = $data['file']->store('document/work-instructions', 'public');
                $url = Storage::url($path);
            } else {
                $url = null; // Handle jika tidak ada file
            }

            // Membuat WorkInstruction
            $workInstructionData = $data;
            $workInstructionData['file'] = $url;

            $workInstruction = WorkInstruction::create($workInstructionData);

            return [
                'success' => true,
                'message' => 'Data is saved successfully.',
                'workInstruction' => $workInstruction
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to save data: ' . $e->getMessage()
            ];
        }
    }


    // update workInstruction
    public function update($id, $data)
    {
        try {
            $workInstruction = WorkInstruction::findOrFail($id);

            $oldFileUrl = $workInstruction->file;

            if (isset($data['file']) && $data['file']->isValid()) {
                // Simpan file baru
                $path = $data['file']->store('document/work-instructions', 'public');
                $fileUrl = Storage::url($path);

                // Hapus file lama jika ada
                if ($oldFileUrl && Storage::disk('public')->exists(str_replace('/storage/', '', $oldFileUrl))) {
                    Storage::disk('public')->delete(str_replace('/storage/', '', $oldFileUrl));
                }
            } else {
                // Jika tidak ada file baru, tetap gunakan file lama
                $fileUrl = $oldFileUrl;
            }

            // Update WorkInstruction
            $workInstruction->update([
                'file' => $fileUrl,
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

    public function destroy(WorkInstruction $workInstruction)
    {
        try {
            // Delete file if exists
            if ($workInstruction->file && Storage::disk('public')->exists(str_replace('/storage/', '', $workInstruction->file))) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $workInstruction->file));
            }

            // delete workInstruction
            $workInstruction->delete();

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

