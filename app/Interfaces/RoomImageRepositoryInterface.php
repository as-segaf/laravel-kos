<?php

namespace App\Interfaces;

interface RoomImageRepositoryInterface
{
    public function createRoomImage($room_id, $fileName);

    public function updateById($fileName, $id);

    public function deleteById($id);
}
