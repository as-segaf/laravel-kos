<?php

namespace App\Repositories;

use App\Http\Resources\RoomResource;
use App\Interfaces\RoomRepositoryInterface;
use App\Models\Room;
use Carbon\Carbon;

class RoomRepository implements RoomRepositoryInterface
{
    public function getAllRooms()
    {
        return RoomResource::collection(Room::with('roomImage')->get());
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

    public function updateRoomUser($data, $id)
    {
        $room = Room::findOrFail($id);
        $room->used_by = $data['user_id'];

        if ($room->used_by == $data['user_id']) {
            $room->used_until = Carbon::parse($room->used_until)->addMonths($data['duration_in_month']);
        } else {
            $room->used_until = Carbon::now()->addMonths($data['duration_in_month']);
        }

        if (!$room->save()) {
            throw new Exception("Failed to update room user", 1);
        }

        return new RoomResource($room);
    }
}
