<?php

namespace App\Containers\AppSection\Cart\UI\API\Controllers;

use Apiato\Core\Exceptions\CoreInternalErrorException;
use Apiato\Core\Exceptions\InvalidTransformerException;
use App\Containers\AppSection\Cart\Actions\AddToCartAction;
use App\Containers\AppSection\Cart\Actions\DeleteCartAction;
use App\Containers\AppSection\Cart\Actions\FindCartByIdAction;
use App\Containers\AppSection\Cart\Actions\GetAllCartsAction;
use App\Containers\AppSection\Cart\Actions\UpdateCartAction;
use App\Containers\AppSection\Cart\UI\API\Requests\AddCartRequest;
use App\Containers\AppSection\Cart\UI\API\Requests\DeleteCartRequest;
use App\Containers\AppSection\Cart\UI\API\Requests\FindCartByIdRequest;
use App\Containers\AppSection\Cart\UI\API\Requests\GetAllCartsRequest;
use App\Containers\AppSection\Cart\UI\API\Requests\UpdateCartRequest;
use App\Containers\AppSection\Cart\UI\API\Transformers\AddToCartTransformer;
use App\Containers\AppSection\Cart\UI\API\Transformers\CartTransformer;
use App\Ship\Exceptions\CreateResourceFailedException;
use App\Ship\Exceptions\DeleteResourceFailedException;
use App\Ship\Exceptions\NotFoundException;
use App\Ship\Exceptions\UpdateResourceFailedException;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use Prettus\Repository\Exceptions\RepositoryException;

class Controller extends ApiController
{
    /**
     * @throws InvalidTransformerException
     * @throws CreateResourceFailedException
     */
    public function addToCart(AddCartRequest $request): JsonResponse
    {
        $cart = app(AddToCartAction::class)->run($request);

        return $this->created($this->transform($cart, AddToCartTransformer::class));
    }

    /**
     * @throws InvalidTransformerException
     * @throws NotFoundException
     */
    public function findCartById(FindCartByIdRequest $request): array
    {
        $cart = app(FindCartByIdAction::class)->run($request);

        return $this->transform($cart, CartTransformer::class);
    }

    /**
     * @throws InvalidTransformerException
     * @throws CoreInternalErrorException
     * @throws RepositoryException
     */
    public function showCart(GetAllCartsRequest $request): array
    {
        $carts = app(GetAllCartsAction::class)->run($request);

        return $this->transform($carts, CartTransformer::class);
    }


    /**
     * @throws InvalidTransformerException
     * @throws UpdateResourceFailedException
     */
    public function updateCart(UpdateCartRequest $request): array
    {
        $cart = app(UpdateCartAction::class)->run($request);

        return $this->transform($cart, CartTransformer::class);
    }

    /**
     * @throws DeleteResourceFailedException
     */
    public function deleteCartItem(DeleteCartRequest $request): array
    {
        $cart = app(DeleteCartAction::class)->run($request);

        return $cart;
    }

}
