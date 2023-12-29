<?php

namespace App\Containers\AppSection\Product\UI\API\Transformers;

use App\Containers\AppSection\Product\Models\Product;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

class ProductTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [

    ];

    protected array $availableIncludes = [

    ];

    public function transform(Product $product): array
    {
        $response = [
            'object' => $product->getResourceKey(),
            'id' => $product->id,
        ];

        return $this->ifAdmin([
            'real_id' => $product->id,
            'name' => $product->created_at,
            'images' => url($product->images,),
            'description' => $product->description,
            'price' => $product->price,
            'sale_price' => $product->sale_price,
            'updated_at' => $product->updated_at,
            'readable_created_at' => $product->created_at->diffForHumans(),
            'readable_updated_at' => $product->updated_at->diffForHumans(),
            // 'deleted_at' => $product->deleted_at,
        ], $response);
    }
}
