<?php

namespace App\Containers\AppSection\Cart\Tasks;

use App\Containers\AppSection\Cart\Models\Cart;
use App\Containers\AppSection\Product\Models\Product;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AddToCartTask extends ParentTask
{
    public function __construct()
    {
        // ..
    }

    public function run(array $request): Cart|JsonResponse
    {
        $productId = $request['product_id'];
        $quantity = $request['quantity'];

        // Get the authenticated user
        $user = Auth::user();

        // Find the product
        $product = Product::find($productId);

//        if (!$product) {
//            return response()->json([
//                'message' => 'Product not found.',
//            ], 404);
//        }


        // Check if the product is already in the cart
        $existingCartItem = Cart::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->where('status', 1)
            ->first();

        if ($existingCartItem) {
            // Update the existing cart item
            $existingCartItem->quantity += $quantity;
            $price = $product->sale_price ?: $product->price;
            $existingCartItem->total = $existingCartItem->quantity * $price;
            $existingCartItem->save();
        } else {
            // Product is not in the cart, add a new item
            $price = $product->sale_price ?: $product->price;
            $cart = Cart::create([
                'user_id' => $user->id,
                'order_id' => null,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'quantity' => $quantity,
                'price' => $price,
                'total' => $quantity * $price,
                'status' => 1,
            ]);

            if (!$cart) {
                return response()->json([
                    'message' => 'Failed to add product to cart.',
                ], 403);
            }
        }

        // Cập nhật tổng giá trị đơn đặt hàng
        $Total = Cart::where('user_id', $user->id)
            ->where('order_id', null)->sum('total');

        return response()->json([
            'message' => 'Product added to the shopping cart.',
            'cart' => $cart,
            ]);
    }
}
