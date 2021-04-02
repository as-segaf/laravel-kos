<?php

namespace App\Interfaces;

interface RoomImageRepositoryInterface
{
    public function createRoomImage($request);

    public function updateById($request, $id);

    public function deleteById($id);
}
