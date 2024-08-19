<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CalibrationLogbookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'product_id' => 1,
                'date' => '2021-08-11',
                'technician' => 'John Doe',
                'institution' => 'Calibration Agency',
                'document' => 'https://example.com/document.pdf',
            ],
            [
                'product_id' => 2,
                'date' => '2021-08-12',
                'technician' => 'Jane Doe',
                'institution' => 'Calibration Agency',
                'document' => 'https://example.com/document.pdf',
            ],
            [
                'product_id' => 3,
                'date' => '2021-08-13',
                'technician' => 'John Doe',
                'institution' => 'Calibration Agency',
                'document' => 'https://example.com/document.pdf',
            ],
        ];

        foreach ($data as $item) {
            \App\Models\CalibrationLogbook::create($item);
        }
    }
}
