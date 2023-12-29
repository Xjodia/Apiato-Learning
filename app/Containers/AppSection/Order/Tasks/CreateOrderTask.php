<?php

namespace App\Containers\AppSection\Order\Tasks;

use App\Containers\AppSection\Cart\Models\Cart;
use App\Containers\AppSection\Order\Data\Repositories\OrderRepository;
use App\Containers\AppSection\Order\Exceptions\OrderFailedException;
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
    public function run(array $data): Order
    {
        $user = Auth::user();
        $user_id = $user->id;

        // Lấy danh sách các mục giỏ hàng chưa được đặt hàng (order_id là null)
        $cartItems = $this->repository->cartsItem($user_id);

        if ($cartItems->isEmpty()) {
            throw (new OrderFailedException())->withErrors(['message' => 'Cart is empty.'], 400);
        }

        // Cập nhật tổng giá trị đơn đặt hàng
        $total = Cart::where('user_id', $user_id)
            ->where('order_id', null)->sum('total');

        // Bắt đầu giao dịch để đảm bảo tính toàn vẹn
        DB::beginTransaction();
        // Kiểm tra số lượng sản phẩm có đủ để trừ không
        foreach ($cartItems as $cartItem) {
            $product = $cartItem->product;
            if ($product->qty < $cartItem->quantity) {
                throw (new OrderFailedException())->withErrors(['message' => 'Insufficient quantity for product: ' . $product->name], 400);
            }
        }
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
            throw (new OrderFailedException())->withErrors(['message' => 'Failed to checkout.'], 500);
        }
    }
}
