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

    public function findRoomById($id)
    {
        $room = Room::findOrFail($id);

        return new RoomResource($room);
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

    public function updateRoom($request, $id)
    {
        $room = Room::findOrFail($id);
        $room->name = $request->name;
        $room->description = $request->description;
        $room->length = $request->length;
        $room->width = $request->width;
        $room->status = $request->status;

        if (!$room->save()) {
           throw new Exception("Error Processing Request", 1);
        }

        return new RoomResource($room);
    }

    public function deleteRoomById($id)
    {
        $room = Room::findOrFail($id);
        
        if (!$room->delete()) {
            throw new Exception("Error Processing Request", 1);
        }

        return new RoomResource($room);
    }
}
