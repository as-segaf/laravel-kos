<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoomImageRequest;
use App\Services\RoomImageService;
use App\Traits\ResponseStructure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class RoomImageController extends Controller
{
    use ResponseStructure;

    protected $roomImageService;

    public function __construct(RoomImageService $roomImageService)
    {
        return $this->roomImageService = $roomImageService;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoomImageRequest $request)
    {
        try {
            $data = $this->roomImageService->store($request);
        } catch (AuthorizationException $exception) {
            return $this->errorResponse(403, $exception->getMessage());
        } catch (\Exception $exception) {
            return $this->errorResponse(500, $exception->getMessage());
        }

        return $this->successResponse(200, 'success', $data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RoomImageRequest $request, $id)
    {
        try {
            $data = $this->roomImageService->update($request, $id);
        } catch (AuthorizationException $exception) {
            return $this->errorResponse(403, $exception->getMessage());
        } catch (\Exception $exception) {
            return $this->errorResponse(500, $exception->getMessage());
        }

        return $this->successResponse(200, 'success', $data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
