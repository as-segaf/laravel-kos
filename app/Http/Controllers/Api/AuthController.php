<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegistrationRequest;
use App\Interfaces\UserInterface;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $userRepository;

    public function __construct(UserInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(RegistrationRequest $request)
    {
        try {
            $user = $this->userRepository->createUser($request);
        } catch (\Exception $exception) {
            return response()->json([
                'code' => 500,
                'message' => $exception->getMessage(),
            ],500);
        }

        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => $user
        ],200);
    }
}
