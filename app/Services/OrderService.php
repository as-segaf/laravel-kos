<?php

namespace App\Services;

use App\Interfaces\OrderRepositoryInterface;
use App\Interfaces\RoomRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;

class OrderService
{
    protected $orderRepository;
    protected $roomRepository;

    public function __construct(OrderRepositoryInterface $orderRepository, RoomRepositoryInterface $roomRepository)
    {
        $this->roomRepository = $roomRepository;
        $this->orderRepository = $orderRepository;
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
        if (! Gate::allows('isAdmin')) {
            throw new AuthorizationException('You are not allowed to do this action.');
        }

        $order = $this->orderRepository->updateStatusOrder($request, $id);

        if ($order->status == 'paid') {
            $updateData = [
                'user_id' => $order->user_id,
                'duration_in_month' => $order->duration_in_month
            ];
            $this->roomRepository->updateRoomUser($updateData, $order->id);
        }

        return $order;
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
