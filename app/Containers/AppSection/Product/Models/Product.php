<?php

namespace App\Containers\AppSection\Product\Models;

use App\Ship\Parents\Models\Model as ParentModel;

class Product extends ParentModel
{
    protected $fillable = [
        'name',
        'images',
        'description',
        'qty',
        'category_id',
        'price',
        'sale_price',
    ];

    protected $hidden = [

    ];

    protected $casts = [

    ];

    /**
     * A resource key to be used in the serialized responses.
     */
    protected string $resourceKey = 'Product';
}
