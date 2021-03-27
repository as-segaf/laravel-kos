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
}
