<?php

namespace App\Containers\AppSection\Order\UI\API\Transformers;

use App\Containers\AppSection\Order\Models\Order;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

class GetOrderUserTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [

    ];

    protected array $availableIncludes = [

    ];

    public function transform(Order $order): array
    {
        $response = [
            'object' => $order->getResourceKey(),
            'User-Order' => $order,
        ];

        return $this->ifAdmin([
            'real_id' => $order->id,
            'created_at' => $order->created_at,
            'updated_at' => $order->updated_at,
            'readable_created_at' => $order->created_at->diffForHumans(),
            'readable_updated_at' => $order->updated_at->diffForHumans(),
            // 'deleted_at' => $order->deleted_at,
        ], $response);
    }
}
