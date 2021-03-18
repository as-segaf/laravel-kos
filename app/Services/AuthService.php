<?php

namespace App\Services;

use App\Http\Resources\UserResource;
use App\Interfaces\UserInterface;
use Illuminate\Auth\AuthenticationException;

class AuthService
{
    protected $userRepository;

    public function __construct(UserInterface $userRepository)
    {
        $this->userRepository = $userRepository;   
    }

    public function register($request)
    {
        return $this->userRepository->createUser($request);
    }

    public function login($request)
    {
        if (!auth()->attempt($request->only('email','password'))) {
            throw new AuthenticationException('Email and password does not match.');
        }

        $token = auth()->user()->createToken('authToken')->plainTextToken;

        return [
            'user' => new UserResource(auth()->user()),
            'token' => $token
        ];
    }
}
