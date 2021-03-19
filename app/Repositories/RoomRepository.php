<?php

namespace App\Repositories;

use App\Http\Resources\RoomResource;
use App\Interfaces\RoomRepositoryInterface;
use App\Models\Room;

class RoomRepository implements RoomRepositoryInterface
{
    public function getAllRooms()
    {
        return RoomResource::collection(Room::all());
    }
}
