<?php

namespace App\Services;

use App\Interfaces\RoomRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;

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

    public function store($request)
    {
        if (Gate::allows('isAdmin')) {
            return $this->roomRepository->createRoom($request);
        }

        throw new AuthorizationException('You are not allowed to do this action.');
    }

    public function show($id)
    {
        return $this->roomRepository->findRoomById($id);
    }

    public function update($request, $id)
    {
        if (Gate::allows('isAdmin')) {
            return $this->roomRepository->updateRoom($request, $id);
        }

        throw new AuthorizationException('You are not allowed to do this action.');
    }
}
