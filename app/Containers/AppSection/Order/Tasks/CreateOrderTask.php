<?php

namespace App\Containers\AppSection\Order\Tasks;

use App\Containers\AppSection\Cart\Models\Cart;
use App\Containers\AppSection\Order\Data\Repositories\OrderRepository;
use App\Containers\AppSection\Order\Models\Order;
use App\Ship\Exceptions\CreateResourceFailedException;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreateOrderTask extends ParentTask
{
    public function __construct(
        protected OrderRepository $repository
    ) {
    }

    /**
     * @throws CreateResourceFailedException
     */
    public function run(array $data): Order|JsonResponse
    {
        $user = Auth::user();

        // Lấy danh sách các mục giỏ hàng chưa được đặt hàng (order_id là null)
        $cartItems = Cart::where('user_id', $user->id)
            ->where('order_id', null)
            ->where('status', 1)
            ->with('product') // Load thông tin sản phẩm liên quan
            ->get();
        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Cart is empty.'], 400);
        }

        // Cập nhật tổng giá trị đơn đặt hàng
        $total = Cart::where('user_id', $user->id)
            ->where('order_id', null)->sum('total');

        // Bắt đầu giao dịch để đảm bảo tính toàn vẹn
        DB::beginTransaction();

        try {
            // Tạo mới đơn đặt hàng
            $order = Order::create([
                'user_id' => $user->id,
                'total' => $total,
                'place' => $data['place'],
                'phone_number' => $data['phone_number'],
                'status' => 'Pending',
                'notes' => $data['notes'] ?? null,
            ]);

            // Cập nhật order_id cho các mục giỏ hàng của người dùng
            foreach ($cartItems as $cartItem) {
                $cartItem->update(['order_id' => $order->id]);
            }

            // Lưu các thay đổi vào đơn đặt hàng
            $order->save();

            // Cập nhật số lượng sản phẩm và trạng thái của mỗi mục giỏ hàng
            foreach ($cartItems as $cartItem) {
                $product = $cartItem->product;
                $product->qty -= $cartItem->quantity;
                $cartItem->update(['status' => 2]);
                $product->save();
            }
            // Commit giao dịch
            DB::commit();

            return $order;
        } catch (\Exception $e) {
            // Nếu có lỗi, rollback giao dịch và trả về thông báo lỗi
            DB::rollBack();
            // dd($e->getMessage());
            return response()->json(['message' => 'Failed to checkout.'], 500);
        }
    }
}
