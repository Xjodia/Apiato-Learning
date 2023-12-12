<?php

namespace App\Containers\AppSection\Category\Models;

use App\Ship\Parents\Models\Model as ParentModel;

class Category extends ParentModel
{
    protected $fillable = [
        'name'
    ];

    protected $hidden = [

    ];

    protected $casts = [

    ];

    /**
     * A resource key to be used in the serialized responses.
     */
    protected string $resourceKey = 'Category';
}
