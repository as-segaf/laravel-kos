<?php

namespace App\Services;

use App\Http\Resources\UserResource;
use App\Interfaces\UserInterface;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;

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
        // if (!auth()->attempt($request->only('email','password'))) {
        //     throw new AuthenticationException('Email and password does not match.');
        // }

        $user = $this->userRepository->findUserByEmail($request->email);

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw new AuthenticationException('Email and password does not match.');
        }

        $token = $user->createToken('authToken')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }
}
