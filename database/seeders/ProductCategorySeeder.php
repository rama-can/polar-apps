<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Analisis Kimia',
                'slug' => 'analisis-kimia'
            ],
            [
                'name' => 'Aktivitas Biologi',
                'slug' => 'aktivitas-biologi'
            ],
            [
                'name' => 'Bahan Alam',
                'slug' => 'bahan-alam',
            ],
            [
                'name' => 'Produksi & Formulasi',
                'slug' => 'produksi-dan-formulasi'
            ],
            [
                'name' => 'Sintesis',
                'slug' => 'sintesis'
            ],
            [
                'name' => 'Umum',
                'slug' => 'umum'
            ]
        ];
        // 'product_category_id' => ProductCategory::where('name', $category)->first()->id
        foreach ($categories as $category){
            ProductCategory::create([
                'name' => $category['name'],
                'slug' => $category['slug']
            ]);
        }
    }
}
