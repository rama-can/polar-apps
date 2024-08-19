<?php

namespace App\Exports;

use Illuminate\Support\Carbon;
use App\Models\CalibrationLogbook;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class CalibrationLogbookExport implements FromCollection, WithHeadings, WithMapping
{
    use Exportable;

    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        return CalibrationLogbook::query()
            ->with('product')
            ->whereBetween('date', [$this->startDate, $this->endDate])
            ->get(['product_id', 'date', 'technician', 'institution', 'document']);
    }

    public function headings(): array
    {
        return [
            'Tool Name',
            'Date',
            'Technician',
            'Institution',
            'Document',
        ];
    }

    public function map($calibrationLogbook): array
    {
        return [
            $calibrationLogbook->product->name,
            Carbon::parse($calibrationLogbook->date)->format('d-m-Y'),
            $calibrationLogbook->technician,
            $calibrationLogbook->institution,
            $calibrationLogbook->document,
        ];
    }
}
