<?php

namespace App\Containers\AppSection\Cart\Actions;

use App\Containers\AppSection\Cart\Models\Cart;
use App\Containers\AppSection\Cart\Tasks\AddToCartTask;
use App\Containers\AppSection\Cart\UI\API\Requests\CreateCartRequest;
use App\Ship\Parents\Actions\Action as ParentAction;
use Illuminate\Http\JsonResponse;

class AddToCartAction extends ParentAction
{
    public function run(CreateCartRequest $request): Cart|JsonResponse
    {
        $data = $request->sanitizeInput([
            // add your request data here
            'product_id',
            'quantity',
        ]);

        $result = app(AddToCartTask::class)->run($data);

        if ($result instanceof Cart) {
            return response()->json([
                'message' => 'Product added to the shopping cart.',
                'cart' => $result,
            ]);
        } else {
            return $result;
        }

        //        return app(AddToCartTask::class)->run($data);
    }
}
