<?php

namespace App\Containers\AppSection\Cart\Actions;

use Apiato\Core\Exceptions\IncorrectIdException;
use App\Containers\AppSection\Cart\Models\Cart;
use App\Containers\AppSection\Cart\Tasks\CreateCartTask;
use App\Containers\AppSection\Cart\UI\API\Requests\AddCartRequest;
use App\Ship\Exceptions\CreateResourceFailedException;
use App\Ship\Parents\Actions\Action as ParentAction;

class CreateCartAction extends ParentAction
{
    /**
     * @param AddCartRequest $request
     * @return Cart
     * @throws CreateResourceFailedException
     * @throws IncorrectIdException
     */
    public function run(AddCartRequest $request): Cart
    {
        $data = $request->sanitizeInput([
            // add your request data here

        ]);

        return app(CreateCartTask::class)->run($data);
    }
}
