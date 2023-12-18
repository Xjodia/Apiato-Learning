<?php

namespace App\Containers\AppSection\Product\Tasks;

use App\Containers\AppSection\Product\Data\Repositories\ProductRepository;
use App\Containers\AppSection\Product\Models\Product;
use App\Ship\Exceptions\NotFoundException;
use App\Ship\Exceptions\UpdateResourceFailedException;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UpdateProductTask extends ParentTask
{
    public function __construct(
        protected ProductRepository $repository
    ) {
    }

    /**
     * @throws NotFoundException
     * @throws UpdateResourceFailedException
     */
    public function run(array $data, $id): Product
    {
        try {
            DB::beginTransaction();
            $existingProduct = Product::query()->findOrFail($id);
            if (!$existingProduct) {
                throw new ModelNotFoundException();
            }
            if (array_key_exists('images', $data) && $data['images']->isValid()) {
                // Delete the old image
                $existingProduct->deleteImages();
                // Store the new image
                $imageFile = $data['images'];
                $imagePath = $imageFile->store('images', 'public');
                $imageFileName = basename($imagePath);
                // Update the product with the new image path
                $data['images'] = Storage::url('images/' . $imageFileName);
            }
            DB::commit();
            return $this->repository->update($data, $id);
        } catch (ModelNotFoundException) {
            DB::rollBack();
            throw new NotFoundException();
        } catch (Exception) {
            DB::rollBack();
            throw new UpdateResourceFailedException();
        }
    }
}
