<?php

namespace App\Services;

use App\Interfaces\OrderRepositoryInterface;
use App\Models\Order;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;

class OrderService
{
    protected $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        return $this->orderRepository = $orderRepository;
    }

    public function index()
    {
        return $this->orderRepository->getAllUserOrder();
    }

    public function store($request)
    {
        return $this->orderRepository->createOrder($request);
    }

    public function update($request, $id)
    {
        if (Gate::allows('isAdmin')) {
            return $this->orderRepository->updateStatusOrder($request, $id);
        }

        throw new AuthorizationException('You are not allowed to do this action.');
    }

    public function show($id)
    {
        $order = $this->orderRepository->findOrderById($id);

        if (auth()->id() == $order->user_id) {
            return $order;
        }
        
        throw new AuthorizationException('You are not allowed to do this action.');
    }
}
