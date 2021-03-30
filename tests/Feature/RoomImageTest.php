<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RoomImageTest extends TestCase
{
    use RefreshDatabase;

    public function createRoom()
    {
        return Room::create([
            'name' => 'room 1',
            'description' => 'a good room',
            'length' => 2,
            'width' => 1,
            'status' => 'unused'
        ]);
    }

    public function createAdmin()
    {
        return User::create([
            'name' => 'testing',
            'email' => 'testing@testing.com',
            'password' => Hash::make('testing123'),
            'is_admin' => 1
        ]);
    }

    public function createUser()
    {
        return User::create([
            'name' => 'testing',
            'email' => 'testing@testing.com',
            'password' => Hash::make('testing123'),
            'is_admin' => 0
        ]);
    }
}
