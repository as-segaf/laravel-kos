<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegistrationRequest;
use App\Interfaces\UserInterface;
use App\Traits\ResponseStructure;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ResponseStructure;

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
            return $this->error(500,$exception->getMessage());
        }

        return $this->success(200,'success',$user);
    }
}
