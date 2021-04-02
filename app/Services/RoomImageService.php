<?php

namespace App\Services;

use App\Interfaces\RoomImageRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;

class RoomImageService
{
    protected $roomImageRepository;

    public function __construct(RoomImageRepositoryInterface $roomImageRepository)
    {
        return $this->roomImageRepository = $roomImageRepository;
    }

    public function store($request)
    {
        if (Gate::allows('isAdmin')) {
            return $this->roomImageRepository->createRoomImage($request);
        }

        throw new AuthorizationException('You are not allowed to do this action.');
    }

    public function update($request, $id)
    {
        if (Gate::allows('isAdmin')) {
            return $this->roomImageRepository->updateById($request, $id);
        }
        
        throw new AuthorizationException('You are not allowed to do this action.');
    }

    public function destroy($id)
    {
        if (Gate::allows('isAdmin')) {
            return $this->roomImageRepository->deleteById($id);
        }

        throw new AuthorizationException('You are not allowed to do this action.');
    }
}
