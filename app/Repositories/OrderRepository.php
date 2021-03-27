<?php

namespace App\Repositories;

use App\Http\Resources\OrderResource;
use App\Interfaces\OrderRepositoryInterface;
use App\Models\Order;

class OrderRepository implements OrderRepositoryInterface
{
    public function getAllUserOrder()
    {
        $orders = Order::where('user_id', auth()->id())->get();

        return OrderResource::collection($orders);
    }

    public function createOrder($request)
    {
        $order = Order::create([
            'user_id' => auth()->id(),
            'room_id' => $request->room_id,
            'duration_in_month' => $request->duration_in_month,
            'status' => 'unpaid',
        ]);

        return new OrderResource($order);
    }
}
