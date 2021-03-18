<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Services\AuthService;
use App\Traits\ResponseStructure;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ResponseStructure;

    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegistrationRequest $request)
    {
        try {
            $user = $this->authService->register($request);
        } catch (\Exception $exception) {
            return $this->error(500,$exception->getMessage());
        }
        return $this->success(200,'success',$user);
    }
}
