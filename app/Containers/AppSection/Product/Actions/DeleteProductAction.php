<?php

namespace App\Containers\AppSection\Product\Actions;

use App\Containers\AppSection\Product\Models\Product;
use App\Containers\AppSection\Product\Tasks\DeleteProductTask;
use App\Containers\AppSection\Product\UI\API\Requests\DeleteProductRequest;
use App\Ship\Exceptions\DeleteResourceFailedException;
use App\Ship\Exceptions\NotFoundException;
use App\Ship\Parents\Actions\Action as ParentAction;
use Illuminate\Http\JsonResponse;

class DeleteProductAction extends ParentAction
{
    /**
     * @param DeleteProductRequest $request
     * @return int|JsonResponse
     */
    public function run(DeleteProductRequest $request): int | JsonResponse
    {
        $product = Product::query()->find($request->id);
        if ($product == null){
            $response = [
                'message' => 'Product not found',
            ];
            return response()->json($response, 404);
        }else{
            return app(DeleteProductTask::class)->run($request->id);
        }
    }
}
