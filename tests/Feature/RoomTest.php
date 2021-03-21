<?php

namespace Tests\Feature;

use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RoomTest extends TestCase
{
    use RefreshDatabase;

    public function testGuestCanSeeRoomList()
    {
        $this->withoutExceptionHandling();
        Room::create([
                'name' => 'room 1',
                'description' => 'this is good room',
                'length' => 3,
                'width' => 2,
                'status' => 'unused'
        ], [
            'name' => 'room 2',
            'description' => 'this is good room',
            'length' => 3,
            'width' => 2,
            'status' => 'unused'
        ]);
        $this->json('get', '/api/room', ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure([
                'code',
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'description',
                        'length',
                        'width',
                        'status'
                    ]
                ] 
            ]);
    }

    public function testSuccessfulCreateRoom()
    {
        $this->withoutExceptionHandling();
        $user = User::create([
            'name' => 'testing',
            'email' => 'testing@testing.com',
            'password' => Hash::make('testing123'),
            'is_admin' => 1
        ]);

        $room = [
            'name' => 'room 1',
            'description' => 'a good room',
            'length' => 3,
            'width' => 2,
            'status' => 'unused'
        ];

        Sanctum::actingAs($user);

        $this->json('post', '/api/room', $room, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure([
                'code',
                'message',
                'data' => [
                    'id',
                    'name',
                    'description',
                    'length',
                    'width',
                    'status'
                ]
            ]);
            
        $this->assertDatabaseCount('rooms', 1);
        $this->assertDatabaseHas('rooms', $room);
    }

    public function testOnlyAdminCanCreateRoom()
    {
        $this->withoutExceptionHandling();
        $user = User::create([
            'name' => 'testing',
            'email' => 'testing@testing.com',
            'password' => Hash::make('testing123'),
            'is_admin' => 0
        ]);

        $room = [
            'name' => 'room 1',
            'description' => 'a good room',
            'length' => 3,
            'width' => 2,
            'status' => 'unused'
        ];

        Sanctum::actingAs($user);
        
        $this->json('post', '/api/room', $room, ['Accept' => 'application/json'])
            ->assertStatus(403)
            ->assertJson([
                'code' => 403,
                'message' => 'You are not allowed to do this action.'
            ]);
            
        $this->assertDatabaseCount('rooms', 0);
    }

    public function testRequiredFieldsForCreateRoom()
    {
        $user = User::create([
            'name' => 'testing',
            'email' => 'testing@testing.com',
            'password' => Hash::make('testing123'),
            'is_admin' => 0
        ]);

        Sanctum::actingAs($user);

        $this->json('post', '/api/room', ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'name' => ['The name field is required.'],
                    'length' => ['The length field is required.'],
                    'width' => ['The width field is required.'],
                    'status' => ['The status field is required.']
                ]
            ]);
    }
}
