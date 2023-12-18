<?php

namespace App\Containers\AppSection\Product\UI\API\Controllers;

use Apiato\Core\Exceptions\CoreInternalErrorException;
use Apiato\Core\Exceptions\InvalidTransformerException;
use App\Containers\AppSection\Order\Models\Order;
use App\Containers\AppSection\Product\Actions\CreateProductAction;
use App\Containers\AppSection\Product\Actions\DeleteProductAction;
use App\Containers\AppSection\Product\Actions\ExportProductAction;
use App\Containers\AppSection\Product\Actions\FindProductByIdAction;
use App\Containers\AppSection\Product\Actions\GetAllProductsAction;
use App\Containers\AppSection\Product\Actions\UpdateProductAction;
use App\Containers\AppSection\Product\Models\Product;
use App\Containers\AppSection\Product\UI\API\Requests\CreateProductRequest;
use App\Containers\AppSection\Product\UI\API\Requests\DeleteProductRequest;
use App\Containers\AppSection\Product\UI\API\Requests\ExportProductRequest;
use App\Containers\AppSection\Product\UI\API\Requests\FindProductByIdRequest;
use App\Containers\AppSection\Product\UI\API\Requests\GetAllProductsRequest;
use App\Containers\AppSection\Product\UI\API\Requests\UpdateProductRequest;
use App\Containers\AppSection\Product\UI\API\Transformers\ProductTransformer;
use App\Ship\Exceptions\CreateResourceFailedException;
use App\Ship\Exceptions\DeleteResourceFailedException;
use App\Ship\Exceptions\NotFoundException;
use App\Ship\Exceptions\UpdateResourceFailedException;
use App\Ship\Parents\Controllers\ApiController;
use App\Ship\Parents\Requests\Request;
use Illuminate\Http\JsonResponse;
use Prettus\Repository\Exceptions\RepositoryException;
use ReflectionException;

class Controller extends ApiController
{
    /**
     * @throws InvalidTransformerException
     * @throws CreateResourceFailedException
     */
    public function createProduct(CreateProductRequest $request): JsonResponse
    {
        $product = app(CreateProductAction::class)->run($request);

        return $this->created($this->transform($product, ProductTransformer::class));
    }

    /**
     * @throws InvalidTransformerException
     * @throws NotFoundException
     */
    public function findProductById(FindProductByIdRequest $request): array
    {
        $product = app(FindProductByIdAction::class)->run($request);

        return $this->transform($product, ProductTransformer::class);
    }

    /**
     * @throws InvalidTransformerException
     * @throws CoreInternalErrorException
     * @throws RepositoryException
     */
    public function getAllProducts(GetAllProductsRequest $request): array
    {
        $products = app(GetAllProductsAction::class)->run($request);

        return $this->transform($products, ProductTransformer::class);
    }

    /**
     * @throws InvalidTransformerException
     * @throws UpdateResourceFailedException
     */
    public function updateProduct(UpdateProductRequest $request): array
    {
        $product = app(UpdateProductAction::class)->run($request);

        return $this->transform($product, ProductTransformer::class);
    }

    /**
     * @param DeleteProductRequest $request
     * @param Product $id
     * @return JsonResponse
     */
    public function deleteProduct(DeleteProductRequest $request, Product $id,)
    {
        $response = app(DeleteProductAction::class)->run($request);
        return $this->noContent();
    }

    public function sendProductExportByEmail(ExportProductRequest $request): JsonResponse
    {
        $response = app(ExportProductAction::class)->run($request);
        if ($response instanceof JsonResponse){

            return $response;
        }
        $response = [
            'message' => 'Export product and send mail successful',
            'status' => 200,
        ];
        return $this->json($response);
    }
}
