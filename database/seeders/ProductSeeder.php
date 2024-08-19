<?php

namespace Database\Seeders;

use GuzzleHttp\Client;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //read csv file and skip data
        $file = public_path('doc/product-backup.csv');
        $handle = fopen($file, 'r');

        //skip the header row
        fgetcsv($handle);
        $chunksize = 25;
        while(!feof($handle))
        {
            $chunkdata = [];

            for($i = 0; $i<$chunksize; $i++)
            {
                $data = fgetcsv($handle);
                if($data === false)
                {
                    break;
                }

                $chunkdata[] = $data;
            }

            $this->getchunkdata($chunkdata);
        }
        fclose($handle);
    }

    private function getchunkdata($chunkdata)
    {
        $client = new Client();

        foreach($chunkdata as $column){
            $name = $column[0];
            $description = $column[1];
            $description = preg_replace("/\r\n|\r|\n/", '<br>', $description);
            $description = str_replace('\n', ' ', $description);
            $content = $column[2];
            $content = preg_replace("/\r\n|\r|\n/", '<br>', $content);
            $content = str_replace('\n', ' ', $content);
            $category = $column[3];
            $images = $column[4];

            //create new Product
            $existingProduct = Product::where('name', $name)
                ->where('description', $description)
                ->where('content', $content)
                ->first();

            if (!$existingProduct) {
                // Create new Product
                $categoryID = ProductCategory::where('id', $category)->first();
                if (!$categoryID) {
                    continue; // Skip this product if the category doesn't exist
                }

                // Create new Product
                $product = Product::create([
                    'name' => $name,
                    'description' => $description,
                    'content' => $content,
                    'available_stock' => 0,
                    'stock' => 0,
                    'status' => true,
                    'product_category_id' => $categoryID->id,
                ]);

                // Create new ProductImage if product was created successfully
                if ($product) {
                    $productId = $product->id;
                    $imageUrls = explode(', ', $images);
                    foreach ($imageUrls as $image) {
                        ProductImage::create([
                            'product_id' => $productId,
                            'url' => $image,
                        ]);
                    }
                }

                // if ($product) {
                //     $imageUrls = explode(', ', $images);

                //     foreach ($imageUrls as $imageUrl) {
                //         $filename = basename(parse_url($imageUrl, PHP_URL_PATH));
                //         $storagePath = 'images/products/' . $filename;

                //         try {
                //             // Mengunduh gambar menggunakan Guzzle
                //             $response = $client->get($imageUrl, ['sink' => storage_path('app/public/' . $storagePath)]);

                //             // Cek apakah unduhan berhasil
                //             if ($response->getStatusCode() === 200) {
                //                 // Menyimpan informasi gambar ke database
                //                 ProductImage::create([
                //                     'product_id' => $product->id,
                //                     'url' => Storage::url($storagePath),
                //                 ]);
                //             } else {
                //                 // Tangani status HTTP yang tidak berhasil jika perlu
                //                 echo "Gagal mengunduh gambar dari URL: $imageUrl\n";
                //             }
                //         } catch (\Exception $e) {
                //             // Tangani kesalahan saat mengunduh gambar
                //             echo "Kesalahan saat mengunduh gambar dari URL: $imageUrl. Kesalahan: " . $e->getMessage() . "\n";
                //         }
                //     }
                // }
            }
        }
    }
}
