<?php

namespace App\Containers\AppSection\Order\Models;

use App\Containers\AppSection\Cart\Models\Cart;
use App\Containers\AppSection\User\Models\User;
use App\Ship\Parents\Models\Model as ParentModel;

class Order extends ParentModel
{
    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'total',
        'place',
        'phone_number',
        'status',
        'notes',
    ];

    protected $hidden = [

    ];

    protected $casts = [

    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function cart()
    {
        return $this->hasMany(Cart::class, 'order_id');
    }

    /**
     * A resource key to be used in the serialized responses.
     */
    protected string $resourceKey = 'Order';
}
