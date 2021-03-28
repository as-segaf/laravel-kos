<?php

namespace App\Repositories;

use App\Http\Resources\OrderResource;
use App\Interfaces\OrderRepositoryInterface;
use App\Models\Order;
use Carbon\Carbon;

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

        if (!$order) {
            throw new Exception("Error Processing Request", 1);
            
        }

        return new OrderResource($order);
    }

    public function updateStatusOrder($request, $id)
    {
        $order = Order::findOrFail($id);

        $order->status = $request->status;
        $order->time_paid = Carbon::now();

        if (!$order->save()) {
            throw new Exception("Error Processing Request", 1);
        }
    }

    public function findOrderById($id)
    {
        $order = Order::findOrFail($id);

        return new OrderResource($order);
    }
}
