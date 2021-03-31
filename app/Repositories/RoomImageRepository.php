<?php

namespace App\Repositories;

use App\Http\Resources\RoomImageResource;
use App\Interfaces\RoomImageRepositoryInterface;
use App\Models\RoomImage;

class RoomImageRepository implements RoomImageRepositoryInterface
{
    public function createRoomImage($request)
    {
        $data = RoomImage::create([
            'room_id' => $request->room_id,
            'img_name' => $request->img_name
        ]);

        if ($data) {
            return new RoomImageResource($data);
        }
        
        throw new Exception("Error Processing Request", 1);
    }
}
