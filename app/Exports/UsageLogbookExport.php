<?php

namespace App\Exports;

use App\Models\UsageLogbook;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;

class UsageLogbookExport implements FromCollection, WithHeadings, WithMapping
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
        return UsageLogbook::query()
            ->with('product')
            ->where('product_id', $this->id)
            ->whereBetween('date', [$this->startDate, $this->endDate])
            ->get(['product_id', 'date', 'name', 'status', 'total_duration', 'temperature', 'rh', 'note']);
    }

    public function headings(): array
    {
        return [
            'Product',
            'Date',
            'Name',
            'Status',
            'Total Duration',
            'Temperature',
            'RH',
            'Note',
        ];
    }

    public function map($usageLogbook): array
    {
        return [
            $usageLogbook->product->name,
            $usageLogbook->date->format('d-m-Y'),
            $usageLogbook->name,
            $usageLogbook->status,
            $usageLogbook->total_duration->format('H:i'),
            $usageLogbook->temperature,
            $usageLogbook->rh,
            $usageLogbook->note,
        ];
    }
}
