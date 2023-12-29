<?php

namespace App\Containers\AppSection\Cart\Actions;

use App\Containers\AppSection\Cart\Models\Cart;
use App\Containers\AppSection\Cart\Tasks\AddToCartTask;
use App\Containers\AppSection\Cart\UI\API\Requests\AddCartRequest;
use App\Ship\Parents\Actions\Action as ParentAction;
use Illuminate\Http\JsonResponse;

class AddToCartAction extends ParentAction
{
    public function run(AddCartRequest $request): Cart
    {
        $data = $request->sanitizeInput([
            // add your request data here
            'product_id',
            'quantity',
        ]);

        return app(AddToCartTask::class)->run($data);
    }
}
