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
        // Gate::authorize('isAdmin');
        // return $this->roomRepository->createRoom($request);

        if (Gate::allows('isAdmin')) {
            return $this->roomRepository->createRoom($request);
        }

        throw new AuthorizationException('You are not allowed to do this action.');

    }
}
