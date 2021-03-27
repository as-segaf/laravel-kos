<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/room', [RoomController::class, 'index']);
Route::get('/room/{room}', [RoomController::class, 'show']);

Route::middleware('auth:sanctum')->group(function() {
    Route::resource('room', RoomController::class)->only('store', 'update', 'destroy');
    Route::resource('order', OrderController::class)->except('create', 'edit');
    Route::post('/logout', [AuthController::class, 'logout']);
});