<?php

namespace App\Containers\AppSection\Cart\Models;

use App\Ship\Parents\Models\Model as ParentModel;

class Cart extends ParentModel
{
    protected $table = 'carts';
    protected $fillable = [
        'user_id',
        'order_id',
        'product_id',
        'product_name',
        'quantity',
        'price',
        'total',
        'status'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    protected $hidden = [

    ];

    protected $casts = [

    ];

    /**
     * A resource key to be used in the serialized responses.
     */
    protected string $resourceKey = 'Cart';
}
