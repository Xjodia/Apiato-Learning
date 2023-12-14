<?php

namespace App\Containers\AppSection\Cart\Data\Repositories;

use App\Ship\Parents\Repositories\Repository as ParentRepository;

class CartRepository extends ParentRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id' => '=',
        // ...
    ];

    // You can add your own methods or override existing methods here as needed.

    /**
     * Example: Get cart items by user ID.
     *
     * @param int $userId
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCartItemsByUserId($userId)
    {
        return $this->model
            ->where('user_id', $userId)
            ->where('status', 1)
            ->where('order_id', null)
            ->with('product')
            ->get();
    }

    /**
     * Example: Delete cart item by ID.
     *
     * @return int
     */
    public function deleteCartItemById($user_id, $product_id)
    {
        return $this->model
            ->where('user_id', $user_id)
            ->where('product_id', $product_id)
            ->where('order_id', null)
            ->where('status', 1)
            ->first();
    }

    // You can add more methods based on your application's requirements
}
