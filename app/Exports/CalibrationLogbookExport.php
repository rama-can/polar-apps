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

    protected $id;
    protected $startDate;
    protected $endDate;

    public function __construct($id, $startDate, $endDate)
    {
        $this->id = $id;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        return CalibrationLogbook::query()
            ->with('product')
            ->where('product_id', $this->id)
            ->whereBetween('date', [$this->startDate, $this->endDate])
            ->get(['product_id', 'date', 'technician', 'institution', 'document']);
    }

    public function headings(): array
    {
        return [
            'Product',
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
            url($calibrationLogbook->document)
        ];
    }
}
