<?php

namespace App\Containers\AppSection\Cart\Tasks;

use App\Containers\AppSection\Cart\Data\Repositories\CartRepository;
use App\Containers\AppSection\Cart\Models\Cart;
use App\Ship\Exceptions\DeleteResourceFailedException;
use App\Ship\Exceptions\NotFoundException;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class DeleteCartTask extends ParentTask
{
    public function __construct(
        protected CartRepository $repository
    ) {
    }

    /**
     * @throws DeleteResourceFailedException
     * @throws NotFoundException
     */
    public function run($id):array
    {
        try {
            $user = Auth::user();
            $user_id = $user->id;

            // Kiểm tra xem sản phẩm có trong giỏ hàng không
            $cartItem = $this->repository->deleteCartItemById($user_id, $id);

            if (!$cartItem) {
                throw new NotFoundException();
            }

            // Delete the cart item
            $cartItem->delete();
            return ['message' => 'Delete successfully', 'status' => 200,];
        } catch (ModelNotFoundException $e) {
            throw new NotFoundException();
        } catch (\Exception $e) {
            throw new DeleteResourceFailedException();
        }
    }
}
