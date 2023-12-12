<?php

namespace App\Containers\AppSection\Cart\UI\API\Controllers;

use Apiato\Core\Exceptions\CoreInternalErrorException;
use Apiato\Core\Exceptions\InvalidTransformerException;
use App\Containers\AppSection\Cart\Actions\AddToCartAction;
use App\Containers\AppSection\Cart\Actions\CreateCartAction;
use App\Containers\AppSection\Cart\Actions\DeleteCartAction;
use App\Containers\AppSection\Cart\Actions\FindCartByIdAction;
use App\Containers\AppSection\Cart\Actions\GetAllCartsAction;
use App\Containers\AppSection\Cart\Actions\UpdateCartAction;
use App\Containers\AppSection\Cart\Models\Cart;
use App\Containers\AppSection\Cart\UI\API\Requests\CreateCartRequest;
use App\Containers\AppSection\Cart\UI\API\Requests\DeleteCartRequest;
use App\Containers\AppSection\Cart\UI\API\Requests\FindCartByIdRequest;
use App\Containers\AppSection\Cart\UI\API\Requests\GetAllCartsRequest;
use App\Containers\AppSection\Cart\UI\API\Requests\UpdateCartRequest;
use App\Containers\AppSection\Cart\UI\API\Transformers\CartTransformer;
use App\Containers\AppSection\Product\Models\Product;
use App\Ship\Exceptions\CreateResourceFailedException;
use App\Ship\Exceptions\DeleteResourceFailedException;
use App\Ship\Exceptions\NotFoundException;
use App\Ship\Exceptions\UpdateResourceFailedException;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Prettus\Repository\Exceptions\RepositoryException;

class Controller extends ApiController
{
    /**
     * @throws InvalidTransformerException
     * @throws CreateResourceFailedException
     */
    public function createCart(CreateCartRequest $request): JsonResponse
    {
        $cart = app(CreateCartAction::class)->run($request);

        return $this->created($this->transform($cart, CartTransformer::class));
    }

    public function addToCart(CreateCartRequest $request): JsonResponse
    {
        $cart = app(AddToCartAction::class)->run($request);

        if ($cart instanceof JsonResponse) {
            return $cart;
        }

        return $this->created([
            'message' => 'Product added to the shopping cart.',
            'cart' => $cart,
        ]);
        //        return $this->created($this->transform($cart, CartTransformer::class));
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
    public function getAllCarts(GetAllCartsRequest $request): array
    {
        $carts = app(GetAllCartsAction::class)->run($request);

        return $this->transform($carts, CartTransformer::class);
    }

    public function showCart(): JsonResponse
    {
        $user = Auth::user();
        $cartItems = Cart::where('user_id', $user->id)
            ->where('status', 1)
            ->where('order_id', null)
            ->with('product')->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Cart is empty.']);
        }

        return response()->json(['cartItems' => $cartItems]);
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
    public function deleteCart(DeleteCartRequest $request): JsonResponse
    {
        app(DeleteCartAction::class)->run($request);

        return $this->noContent();
    }

    public function deleteCartItem(Request $request): JsonResponse
    {
        $productId = $request->input('product_id');
        $user = Auth::user();
        $product = Product::find($productId);
        // Kiểm tra nếu sản phẩm có trong giỏ hàng của người dùng
        $cartItem = Cart::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->where('order_id', null)
            ->where('status', 1) // Chỉ xóa sản phẩm nếu nó đang hoạt động trong giỏ hàng
            ->first();
        if (!$cartItem) {
            return response()->json([
                'message' => 'Product not found in the cart.',
            ], 404);
        }

        // Xóa sản phẩm khỏi giỏ hàng
        $cartItem->delete();

        return response()->json(['message' => 'Product removed from the shopping cart.']);
    }
}
