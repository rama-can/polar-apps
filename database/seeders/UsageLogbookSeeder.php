<?php

namespace Database\Seeders;

use App\Models\UsageLogbook;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsageLogbookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'product_id' => 1,
                'date' => date('Y-m-d'),
                'name' => 'John Doe',
                'status' => 'MAHASISWA',
                'total_duration' => date('H:i', time()),
                'temperature' => '25',
                'rh' => '50',
                'note' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            ],
            [
                'product_id' => 1,
                'date' => date('Y-m-d'),
                'name' => 'Jane Doe',
                'status' => 'DOSEN',
                'total_duration' => date('H:i', time()),
                'temperature' => '25',
                'rh' => '50',
                'note' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            ],
            [
                'product_id' => 1,
                'date' => date('Y-m-d'),
                'name' => 'John Doe',
                'status' => 'PLP',
                'total_duration' => date('H:i', time()),
                'temperature' => '25',
                'rh' => '50',
                'note' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            ],
            [
                'product_id' => 4,
                'date' => date('Y-m-d'),
                'name' => 'Jane Doe',
                'status' => 'MAHASISWA',
                'total_duration' => date('H:i', time()),
                'temperature' => '25',
                'rh' => '50',
                'note' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            ],
        ];

        UsageLogbook::insert($data);
    }
}
