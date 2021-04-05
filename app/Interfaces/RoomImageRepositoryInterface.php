<?php

namespace App\Interfaces;

interface RoomImageRepositoryInterface
{
    public function createRoomImage($room_id, $fileName);

    public function updateById($request, $id);

    public function deleteById($id);
}
