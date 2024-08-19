<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Http\Controllers\Controller;

class ImportController extends Controller
{

    public function __invoke(Request $request)
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

        return redirect()->back()->with('success', 'Data has been added successfully.');
    }

    private function getchunkdata($chunkdata)
    {
        foreach($chunkdata as $column){
            $name = $column[0];
            $description = $column[1];
            $content = $column[2];
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
            }
        }
    }
}
