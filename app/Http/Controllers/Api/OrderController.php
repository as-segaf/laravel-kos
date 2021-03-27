<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Services\OrderService;
use App\Traits\ResponseStructure;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use ResponseStructure;

    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        return $this->orderService = $orderService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $datas = $this->orderService->index();
        } catch (\Exception $exception) {
            return $this->errorResponse(500, $exception->getMessage());
        }

        return $this->successResponse(200, 'success', $datas);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrderRequest $request)
    {
        try {
            $data = $this->orderService->store($request);
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
    public function update(Request $request, $id)
    {
        //
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
