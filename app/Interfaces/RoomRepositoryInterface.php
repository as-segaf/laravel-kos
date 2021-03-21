<?php

namespace App\Interfaces;

interface RoomRepositoryInterface
{
    public function getAllRooms();

    public function createRoom($request);
}
