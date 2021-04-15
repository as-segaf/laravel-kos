<?php

namespace App\Repositories;

use App\Http\Resources\RoomImageResource;
use App\Interfaces\RoomImageRepositoryInterface;
use App\Models\RoomImage;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RoomImageRepository implements RoomImageRepositoryInterface
{
    public function createRoomImage($room_id, $fileName)
    {
        $data = RoomImage::create([
            'room_id' => $room_id,
            'img_name' => $fileName
        ]);

        if ($data) {
            return new RoomImageResource($data);
        }
        
        throw new Exception("Error Processing Request", 1);
    }

    public function updateById($fileName, $id)
    {
        $roomImage = RoomImage::findOrFail($id);

        $roomImage->img_name = $fileName;

        if (!$roomImage->save()) {
            throw new Exception("Error Processing Request", 1);
        }

        return new RoomImageResource($roomImage);
    }

    public function deleteById($id)
    {
        $roomImage = RoomImage::findOrFail($id);

        if (!$roomImage->delete()) {
            throw new Exception("Error Processing Request", 1);
        }
        
        return new RoomImageResource($roomImage);
    }
}
