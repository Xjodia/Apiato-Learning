<?php

namespace App\Containers\AppSection\Product\Data\Repositories;

use App\Ship\Parents\Repositories\Repository as ParentRepository;

class ProductRepository extends ParentRepository
{

    protected string $container = 'Product';

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id' => '=',
        'name',
        'email',
        // ...
    ];
}
