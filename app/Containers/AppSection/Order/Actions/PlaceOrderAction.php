<?php

namespace App\Containers\AppSection\Order\Actions;

use App\Containers\AppSection\Order\Models\Order;
use App\Containers\AppSection\Order\Tasks\CreateOrderTask;
use App\Containers\AppSection\Order\UI\API\Requests\CreateOrderRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

class PlaceOrderAction extends ParentAction
{
    public function run(CreateOrderRequest $request): Order
    {
        $data = $request->sanitizeInput([
            // add your request data here
            'place',
            'phone_number',
            'notes',
        ]);

        return app(CreateOrderTask::class)->run($data);
    }
}
