<?php

namespace App\Interfaces;

interface RoomRepositoryInterface
{
    public function getAllRooms();

    public function findRoomById($id);

    public function createRoom($request);

    public function updateRoom($request, $id);
}
