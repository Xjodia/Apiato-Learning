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
        $user = Auth::user();
        // Tìm kiếm sản phẩm. Không tìm thấy, sẽ throw ngoại lệ.
        $product = Product::findOrFail($productId);

        try {
            $cart = Cart::where('user_id', $user->id)
                ->where('product_id', $product->id)
                ->where('status', 1)
        // Tìm kiếm hoặc tạo mới Cart
                ->firstOrNew([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                ]);

            $price = $product->sale_price ?: $product->price;
            $cart->quantity += $quantity;
            $cart->total = $cart->quantity * $price;
            $cart->fill([
                'product_name' => $product->name,
                'price' => $price,
                'status' => 1,
            ])->save();
        } catch (\Exception $e) {
            throw new CreateResourceFailedException();
        }

        return $cart;
    }
}
