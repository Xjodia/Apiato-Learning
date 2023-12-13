<?php

namespace App\Containers\AppSection\Product\Tasks;

use App\Containers\AppSection\Product\Data\Repositories\ProductRepository;
use App\Containers\AppSection\Product\Models\Product;
use App\Ship\Exceptions\CreateResourceFailedException;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CreateProductTask extends ParentTask
{
    public function __construct(
        protected ProductRepository $repository
    ) {
    }

    /**
     * @throws CreateResourceFailedException
     */
    public function run(array $data): Product | JsonResponse | array
    {
        try {
            DB::beginTransaction();
            $newProduct = $data;
            if (array_key_exists('images', $newProduct) && $newProduct['images']->isValid()) {
                $imageFile = $newProduct['images'];
                $imagePath = $imageFile->store('images', 'public');
                $imageFileName = basename($imagePath);
                // Update the product with the image path
                $newProduct['images'] = Storage::url('images/' . $imageFileName);
            }
            $this->repository->create($newProduct);
            DB::commit();
            return $newProduct;
        } catch (Exception) {
            DB::rollBack();
            throw new CreateResourceFailedException();
        }
    }
}
