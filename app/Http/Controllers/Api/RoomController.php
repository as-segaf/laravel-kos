<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoomRequest;
use App\Services\RoomService;
use App\Traits\ResponseStructure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    use ResponseStructure;

    protected $roomService;

    public function __construct(RoomService $roomService)
    {
        return $this->roomService = $roomService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $data = $this->roomService->index();
        } catch (\Exception $exception) {
            return $this->errorResponse(500, $exception->getMessage());
        }

        return $this->successResponse(200, 'success', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoomRequest $request)
    {
        try {
            $data = $this->roomService->store($request);
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
        try {
            $data = $this->roomService->show($id);
        } catch (\Exception $exception) {
            return $this->errorResponse(500, $exception->getMessage());
        }

        return $this->successResponse(200, 'success', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RoomRequest $request, $id)
    {
        try {
            $data = $this->roomService->update($request, $id);
        } catch (AuthorizationException $exception) {
            return $this->errorResponse(403, $exception->getMessage());
        } catch (ModelNotFoundException $exception) {
            return $this->errorResponse(404, 'Room not found');
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
