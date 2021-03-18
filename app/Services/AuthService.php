<?php

namespace App\Services;

use App\Interfaces\UserInterface;

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
}
