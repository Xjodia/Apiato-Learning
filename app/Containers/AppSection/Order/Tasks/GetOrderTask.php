<?php

namespace App\Containers\AppSection\Order\Tasks;

use App\Containers\AppSection\Order\Data\Repositories\OrderRepository;
use App\Containers\AppSection\Order\Exceptions\OrderFailedException;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Illuminate\Support\Facades\Auth;

class GetOrderTask extends ParentTask
{
    public function __construct(protected OrderRepository $repository)
    {
    }

    /**
     * @throws OrderFailedException
     */
    public function run(): mixed
    {
        $user = Auth::user();
        $user_id = $user->id;
        $cartItem = $this->repository->getUserOrder($user_id);

        if (null === $cartItem) {
            throw (new OrderFailedException())->withErrors(['message' => 'Cart is empty.'], 400);
        }

        return $cartItem;
    }
}
