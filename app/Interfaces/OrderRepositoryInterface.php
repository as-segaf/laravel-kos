<?php

namespace App\Interfaces;

interface OrderRepositoryInterface
{
    public function getAllUserOrder();

    public function createOrder($request);

    public function updateStatusOrder($request, $id);
}
