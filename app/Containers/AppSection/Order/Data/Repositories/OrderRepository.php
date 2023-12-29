<?php

namespace App\Containers\AppSection\Order\Data\Repositories;

use App\Containers\AppSection\Cart\Models\Cart;
use App\Containers\AppSection\Order\Models\Order;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

class OrderRepository extends ParentRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id' => '=',
        // ...
    ];

    public function cartsItem($userId)
    {
        return Cart::where('user_id', $userId)
            ->where('order_id', null)
            ->where('status', 1)
            ->with('product') // Load thông tin sản phẩm liên quan
            ->get();
    }

    public function getUserOrder($userId)
    {
        return Order::with('cart.product')->where('user_id', $userId)
            ->orderBy('id', 'DESC')
            ->first();
        //  hoặc dùng
        //  ->latest()->first();
    }

}
