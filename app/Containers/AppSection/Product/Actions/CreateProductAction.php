<?php

namespace App\Containers\AppSection\Product\Actions;

use Apiato\Core\Exceptions\IncorrectIdException;
use App\Containers\AppSection\Product\Models\Product;
use App\Containers\AppSection\Product\Tasks\CreateProductTask;
use App\Containers\AppSection\Product\UI\API\Requests\CreateProductRequest;
use App\Ship\Exceptions\CreateResourceFailedException;
use App\Ship\Parents\Actions\Action as ParentAction;
use Illuminate\Support\Facades\Storage;

class CreateProductAction extends ParentAction
{
    /**
     * @throws CreateResourceFailedException
     * @throws IncorrectIdException
     */
    public function run(CreateProductRequest $request): Product
    {
        $fields = [
            'name',
            'images',
            'description',
            'category_id',
            'qty',
            'price',
            'sale_price',
        ];

        $data = $request->sanitizeInput($fields);
        // Create the product in the database
        $product = app(CreateProductTask::class)->run($data);

        // Upload the image if it exists
        if ($request->hasFile('images')) {
            $imageFile = $request->file('images');
            $imagePath = $imageFile->store('images', 'public');
            $imageFileName = basename($imagePath);

            // Update the product with the image path
            $product->update(['images' => Storage::url('images/' . $imageFileName)]);
        }
        return $product;
    }
}
