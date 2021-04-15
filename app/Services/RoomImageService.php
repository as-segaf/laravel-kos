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
        if (! Gate::allows('isAdmin')) {
            throw new AuthorizationException('You are not allowed to do this action.');
        }

        $fileName = $this->moveImageToPulic($request->file('img_name'));

        return $this->roomImageRepository->createRoomImage($request->room_id, $fileName);
    }

    public function update($request, $id)
    {
        if (! Gate::allows('isAdmin')) {
            throw new AuthorizationException('You are not allowed to do this action.');
        }

        if (\File::exists(public_path('images/'.$request->oldFile))) {
            $this->deleteImage($request->oldFile);
        }

        $fileName = $this->moveImageToPulic($request->file('img_name'));
        
        return $this->roomImageRepository->updateById($fileName, $id);
    }

    public function destroy($id)
    {
        if (Gate::allows('isAdmin')) {
            return $this->roomImageRepository->deleteById($id);
        }

        throw new AuthorizationException('You are not allowed to do this action.');
    }

    public function deleteImage($fileName)
    {
        if (!\File::delete(public_path('images/'.$fileName))) {
            throw new \Exception("Failed to delete image", 1);
        }

        return $fileName;
    }

    public function moveImageToPulic($file)
    {
        $fileName = 'room_image_'.pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME).'-'.time().'.'.$file->getClientOriginalExtension();

        if (! $file->move(public_path('images'), $fileName)) {
            throw new \Exception("Failed to move image to the path", 1);
        }

        return $fileName;
    }
}
