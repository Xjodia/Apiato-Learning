<?php

namespace App\Containers\AppSection\Product\Tasks;

use App\Containers\AppSection\Product\Data\Repositories\ProductRepository;
use App\Containers\AppSection\Product\Jobs\ExportJob;
use App\Ship\Parents\Exceptions\Exception;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ExportProductTask extends ParentTask
{
    public function __construct(
        protected ProductRepository $repository
    ) {
    }

    /**
     * @throws Exception
     */
    public function run(array $data): JsonResponse
    {
        try {
            DB::beginTransaction();
            $products = $this->repository->get();
            if (!$products) {
                DB::rollBack();
                return response()->json('Product is Empty', 404);
            }
            ExportJob::dispatch($products, $data['email']);
            DB::commit();
            return response()->json('Successfully (TasK)', 200);
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
