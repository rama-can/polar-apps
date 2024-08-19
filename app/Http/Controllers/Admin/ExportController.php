<?php

namespace App\Http\Controllers\Admin;

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
        $request->validate([
            'from_date_usage' => 'required|date',
            'to_date_usage' => 'required|date'
        ]);

        $startDate = $request->from_date_usage;
        $endDate = $request->to_date_usage;

        $fileName = 'usage_logbook_' . $startDate . '_' . $endDate . '.xlsx';
        return Excel::download(new UsageLogbookExport($startDate, $endDate), $fileName);
    }

    public function calibrationLogbook(Request $request)
    {
        $request->validate([
            'from_date_calibration' => 'required|date',
            'to_date_calibration' => 'required|date'
        ]);

        $startDate = $request->from_date_calibration;
        $endDate = $request->to_date_calibration;

        $fileName = 'calibration_logbook_' . $startDate . '_' . $endDate . '.xlsx';
        return Excel::download(new CalibrationLogbookExport($startDate, $endDate), $fileName);
    }
}
