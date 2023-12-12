<?php

namespace App\Containers\AppSection\Product\Models;

use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Support\Facades\Storage;

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

    public function deleteImages(): void
    {
        $file_path =public_path('') .$this->images;
        if(file_exists($file_path)){
            unlink($file_path);
        }
    }
}
