<?php

namespace App\Containers\AppSection\Product\Data\Seeders;

use App\Containers\AppSection\Product\Tasks\CreateProductTask;
use App\Ship\Parents\Seeders\Seeder as ParentSeeder;

class ProductSeeder extends ParentSeeder
{
    public function run()
    {
        $createProductTask = app(CreateProductTask::class);

        for ($i = 1; $i <= 10; $i++) {
            $productData = [
                'name' => 'Product ' . $i,
                'category_id' => 1, // Set your desired category ID
                'images' => null, // Set image data if needed
                'description' => 'Description for Product ' . $i,
                'qty' => 10, // Set your desired quantity
                'price' => 20.99, // Set your desired price
                'sale_price' => 15.99, // Set your desired sale price
            ];
            $createProductTask->run($productData);
        }
    }
}
