<?php

namespace App\Http\Controllers\Admin;

use App\Models\UsageLogbook;
use App\Models\CalibrationLogbook;
use Illuminate\Http\Request;
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
        $data = UsageLogbook::where('product_id', $request->product_id)->firstOrFail();

        $request->validate([
            'from_date_usage' => 'required|date',
            'to_date_usage' => 'required|date'
        ]);

        $startDate = $request->from_date_usage;
        $endDate = $request->to_date_usage;
        $id = $request->product_id;

        $fileName = 'usage_logbook_' . $startDate . '_' . $endDate . '.xlsx';
        return Excel::download(new UsageLogbookExport($id, $startDate, $endDate), $fileName);
    }

    public function calibrationLogbook(Request $request)
    {
        $data = CalibrationLogbook::where('product_id', $request->product_id)->firstOrFail();

        $request->validate([
            'from_date_usage' => 'required|date',
            'to_date_usage' => 'required|date'
        ]);

        $startDate = $request->from_date_usage;
        $endDate = $request->to_date_usage;
        $id = $request->product_id;

        $fileName = 'calibration_logbook_' . $startDate . '_' . $endDate . '.xlsx';
        return Excel::download(new CalibrationLogbookExport($id, $startDate, $endDate), $fileName);
    }
}
