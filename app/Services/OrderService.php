<?php

namespace App\Services;

use App\Interfaces\OrderRepositoryInterface;

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
}
