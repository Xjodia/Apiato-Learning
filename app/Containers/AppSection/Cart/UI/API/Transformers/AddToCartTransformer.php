<?php

namespace App\Containers\AppSection\Cart\UI\API\Transformers;

use App\Containers\AppSection\Cart\Models\Cart;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

class AddToCartTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [
    ];

    protected array $availableIncludes = [
    ];

    public function transform(Cart $cart): array
    {
        $response = [
            'object' => $cart->getResourceKey(),
            'message' => 'Product added to the shopping cart.',
            'cart' => [
                'id' => $cart->id,
                'user_id' => $cart->user_id,
                'product_name' => $cart->product_name,
                'product_id' => $cart->product_id,
                'quantity' => $cart->quantity,
                'price' => $cart->price,
                'total' => $cart->total,
                'status' => $cart->status,
                'created_at' => $cart->created_at,
                'updated_at' => $cart->updated_at,
            ],
        ];

        return $this->ifAdmin([
            'message' => 'Admin...',
            'cart' => [
                'id' => $cart->id,
                'user_id' => $cart->user_id,
                'product_name' => $cart->product_name,
                'product_id' => $cart->product_id,
                'quantity' => $cart->quantity,
                'price' => $cart->price,
                'total' => $cart->total,
                'status' => $cart->status,
                'created_at' => $cart->created_at,
                'updated_at' => $cart->updated_at,
            ]], $response);
    }
}
