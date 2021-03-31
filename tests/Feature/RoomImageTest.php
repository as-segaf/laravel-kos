<?php

namespace Tests\Feature;

use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
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

    public function testSuccessfulAddRoomImage()
    {
        $room = $this->createRoom();
        $admin = $this->createAdmin();
        $data = [
            'room_id' => $room->id,
            'img_name' => 'image1.png'
        ];
        
        Sanctum::actingAs($admin);

        $this->json('post', '/api/roomImage', $data, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure([
                'code',
                'message',
                'data' => [
                    'id',
                    'room_id',
                    'img_name'
                ]
            ]);

        $this->assertDatabaseHas('room_images', $data);
    }

    public function testOnlyAdminCanAddRoomImage()
    {
        $room = $this->createRoom();
        $user = $this->createUser();
        $data = [
            'room_id' => $room->id,
            'img_name' => 'image1.jpg'
        ];

        Sanctum::actingAs($user);

        $this->json('post', '/api/roomImage', $data, ['Accept' => 'application.json'])
            ->assertStatus(403)
            ->assertJson([
                'code' => 403,
                'message' => 'You are not allowed to do this action.'
            ]);

        $this->assertDatabaseCount('room_images', 0);
    }
}
