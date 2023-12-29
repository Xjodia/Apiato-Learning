<?php

namespace App\Containers\AppSection\Cart\Tasks;

use Apiato\Core\Exceptions\CoreInternalErrorException;
use App\Containers\AppSection\Cart\Data\Repositories\CartRepository;
use App\Ship\Exceptions\CreateResourceFailedException;
use App\Ship\Parents\Tasks\Task as ParentTask;
use mysql_xdevapi\Exception;
use Prettus\Repository\Exceptions\RepositoryException;

class GetAllCartsTask extends ParentTask
{
    public function __construct(
        protected CartRepository $repository
    ) {
    }

    /**
     * @throws CoreInternalErrorException
     */
    public function run($id): mixed
    {
        try {
            return $this->repository->getCartItemsByUserId($id);
        } catch (\Exception $exception) {
            throw new CoreInternalErrorException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }
}
