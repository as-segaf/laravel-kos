<?php

namespace App\Services;

use App\Interfaces\OrderRepositoryInterface;
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
}
