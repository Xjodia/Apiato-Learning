<?php

namespace App\Containers\AppSection\Order\Actions;

use App\Containers\AppSection\Order\Models\Order;
use App\Containers\AppSection\Order\Tasks\GetOrderTask;
use App\Ship\Parents\Actions\Action as ParentAction;

class GetOrderAction extends ParentAction
{
    public function run(): Order
    {
        return app(GetOrderTask::class)->run();
    }
}
