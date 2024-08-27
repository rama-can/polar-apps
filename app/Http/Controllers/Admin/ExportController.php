<?php

namespace App\Http\Controllers\Admin;

use App\Models\UsageLogbook;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\CalibrationLogbook;
use App\Exports\UsageLogbookExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CalibrationLogbookExport;

class ExportController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read exports')->only(['index', 'usageLogbook', 'calibrationLogbook']);
    }

    public function index()
    {
        return view('admin.configuration.export.index', [
            'title' => 'Export Data'
        ]);
    }

    public function usageLogbook(Request $request)
    {
        $request->validate([
            'from_date' => 'required|date',
            'to_date' => 'required|date'
        ]);

        $startDate = $request->from_date;
        $endDate = $request->to_date;
        $id = $request->product_id;

        // Periksa apakah tanggal awal lebih besar dari tanggal akhir
        if (Carbon::parse($startDate)->gt(Carbon::parse($endDate))) {
            return redirect()->back()->with('error', 'Tanggal awal tidak boleh lebih besar dari tanggal akhir.');
        }

        // Cek apakah data logbook ada
        $data = UsageLogbook::where('product_id', $id)
            ->whereBetween('date', [$startDate, $endDate])
            ->exists();

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan untuk periode yang dipilih.');
        }
        $fileName = 'usage_logbook_' . $startDate . '_' . $endDate . '.xlsx';

        try {
            return Excel::download(new UsageLogbookExport($id, $startDate, $endDate), $fileName);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengekspor data. Silakan coba lagi.');
        }
    }

    public function calibrationLogbook(Request $request)
    {
        $request->validate([
            'from_date' => 'required|date',
            'to_date' => 'required|date'
        ]);

        $startDate = $request->from_date;
        $endDate = $request->to_date;
        $id = $request->product_id;

        // Periksa apakah tanggal awal lebih besar dari tanggal akhir
        if (Carbon::parse($startDate)->gt(Carbon::parse($endDate))) {
            return redirect()->back()->with('error', 'Tanggal awal tidak boleh lebih besar dari tanggal akhir.');
        }

        // Cek apakah data logbook ada
        $dataExists = CalibrationLogbook::where('product_id', $id)
            ->whereBetween('date', [$startDate, $endDate])
            ->exists();

        if (!$dataExists) {
            return redirect()->back()->with('error', 'Data tidak ditemukan untuk periode yang dipilih.');
        }

        $fileName = 'calibration_logbook_' . $startDate . '_' . $endDate . '.xlsx';

        try {
            return Excel::download(new CalibrationLogbookExport($id, $startDate, $endDate), $fileName);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengekspor data. Silakan coba lagi.');
        }
    }
}
