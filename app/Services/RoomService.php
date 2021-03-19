<?php

namespace App\Services;

use App\Interfaces\RoomRepositoryInterface;

class RoomService
{
    protected $roomRepository;

    public function __construct(RoomRepositoryInterface $roomRepository)
    {
        return $this->roomRepository = $roomRepository;
    }

    public function index()
    {
        return $this->roomRepository->getAllRooms();
    }
}
