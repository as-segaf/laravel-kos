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

    public function createRoom($request)
    {
        $room = Room::create([
            'name' => $request->name,
            'description' => $request->description,
            'length' => $request->length,
            'width' => $request->width,
            'status' => $request->status
        ]);

        if (!$room) {
            throw new Exception("Error Processing Request", 1);
        }

        return new RoomResource($room);
    }
}
