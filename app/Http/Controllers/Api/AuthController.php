<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Services\AuthService;
use App\Traits\ResponseStructure;
use Illuminate\Auth\AuthenticationException;
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
            $data = $this->authService->register($request);
        } catch (\Exception $exception) {
            return $this->errorResponse(500, $exception->getMessage());
        }

        return $this->successResponse(200, 'success', $data);
    }

    public function login(LoginRequest $request)
    {
        try {
            $data = $this->authService->login($request);
        } catch(AuthenticationException $exception) {
            return $this->errorResponse(401, $exception->getMessage());
        } catch (\Exception $exception) {
            return $this->errorResponse(500, $exception->getMessage());
        }

        return $this->successResponse(200, 'Login Successfully', $data['user'], $data['token']);
    }

    public function logout()
    {
        try {
            $this->authService->logout();
        } catch (\Exception $exception) {
            return $this->errorResponse(500, $exception->getMessage());
        }

        return $this->successResponse(200, 'Logout Successfully', []);
    }
}
