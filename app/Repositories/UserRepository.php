<?php

namespace App\Repositories;

use App\Http\Resources\UserResource;
use App\Interfaces\UserInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\AuthenticationException;

class UserRepository implements UserInterface
{
    public function findUserByEmail($email)
    {
        $user = User::where('email', $email)->first();
    
        if (!$user) {
            throw new AuthenticationException('Email and password does not match.');
        }

        return new UserResource($user);
    }

    public function createUser($request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => $request->is_admin
        ]);

        if (!$user) {
            throw new Exception("Error Processing Request", 1);
        }
        
        return new UserResource($user);
    }
}
