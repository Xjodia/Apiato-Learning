<?php

namespace App\Containers\AppSection\Cart\Tasks;

use App\Containers\AppSection\Cart\Models\Cart;
use App\Containers\AppSection\Product\Models\Product;
use App\Ship\Exceptions\CreateResourceFailedException;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Illuminate\Support\Facades\Auth;

class AddToCartTask extends ParentTask
{
    public function __construct()
    {
        // ..
    }

    /**
     * @throws CreateResourceFailedException
     */
    public function run(array $request): Cart
    {
        $productId = $request['product_id'];
        $quantity = $request['quantity'];

        // Get the authenticated user
        $user = Auth::user();

        // Find the product
        $product = Product::find($productId);

        // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
        $existingCartItem = Cart::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->where('status', 1)
            ->first();

        if ($existingCartItem) {
            // Cập nhật số lượng sản phẩm đó
            $existingCartItem->quantity += $quantity;
            $price = $product->sale_price ?: $product->price;
            $existingCartItem->total = $existingCartItem->quantity * $price;
            $existingCartItem->save();

            return $existingCartItem;
        } else {
            try {
                // Sản phẩm không có trong giỏ hàng, thêm sản phẩm mới
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
            } catch (\Exception $e) {
                throw new CreateResourceFailedException();
            }

            return $cart;
        }
    }
}
