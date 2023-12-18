<?php

namespace App\Containers\AppSection\Product\Models;

use App\Containers\AppSection\Cart\Models\Cart;
use App\Containers\AppSection\Category\Models\Category;
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

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function Cart()
    {
        return $this->hasMany(Cart::class, 'product_id');
    }

    /**
     * A resource key to be used in the serialized responses.
     */
    protected string $resourceKey = 'Product';

    public function deleteImages(): void
    {
        $filePath = Storage::url($this->images);
        // Convert the URL to a local path
        $localPath = public_path(ltrim(parse_url($filePath, PHP_URL_PATH), '/'));
        if (file_exists($localPath)) {
            unlink($localPath);
        }
    }
}
