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
        $user = $this->createAdmin();

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
        $user = $this->createUser();

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
        $user = $this->createAdmin();

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

    public function testSuccessfulUpdateRoom()
    {
        $this->withoutExceptionHandling();

        $room = $this->createRoom();

        $updateData = [
            'name' => 'new room',
            'description' => 'a very good room',
            'length' => 5,
            'width' => 4,
            'status' => 'used'
        ];

        $user = $this->createAdmin();
        
        Sanctum::actingAs($user);

        $this->json('patch', '/api/room/'.$room->id, $updateData, ['Accept' => 'application/json'])
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
        
        $this->assertDatabaseMissing('rooms', [
            'name' => 'room 1',
            'description' => 'a good room',
            'length' => 2,
            'width' => 1,
            'status' => 'unused'
        ]);
        $this->assertDatabaseHas('rooms', $updateData);
    }

    public function testOnlyAdminCanUpdateRoom()
    {
        $this->withoutExceptionHandling();

        $room = $this->createRoom();

        $updateData = [
            'name' => 'new room',
            'description' => 'a very good room',
            'length' => 5,
            'width' => 4,
            'status' => 'used'
        ];

        $user = $this->createUser();
        
        Sanctum::actingAs($user);

        $this->json('patch', '/api/room/'.$room->id, $updateData, ['Accept' => 'application/json'])
            ->assertStatus(403)
            ->assertJson([
                'code' => 403,
                'message' => 'You are not allowed to do this action.'
            ]);
        
        $this->assertDatabaseHas('rooms', [
            'name' => 'room 1',
            'description' => 'a good room',
            'length' => 2,
            'width' => 1,
            'status' => 'unused'
        ]);
    }

    public function testRequiredFieldsForUpdateRoom()
    {
        $room = Room::create([
            'name' => 'room 1',
            'description' => 'a good room',
            'length' => 2,
            'width' => 1,
            'status' => 'unused'
        ]);

        $user = User::create([
            'name' => 'testing',
            'email' => 'testing@testing.com',
            'password' => Hash::make('testing123'),
            'is_admin' => 1
        ]);
        
        Sanctum::actingAs($user);

        $this->json('patch', '/api/room/'.$room->id, ['Accept' => 'application/json'])
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

    public function testUpdateWithWrongIdFailed()
    {
        $this->withoutExceptionHandling();

        $room = $this->createRoom();
        $admin = $this->createAdmin();
        $updateData = [
            'name' => 'new room',
            'description' => 'a new updated room',
            'length' => 5,
            'width' => 4,
            'status' => 'used'
        ];

        Sanctum::actingAs($admin);

        $this->json('patch', '/api/room/123', $updateData, ['Accept' => 'application/json'])
            ->assertStatus(404)
            ->assertJsonStructure([
                'code',
                'message'
            ]);
    }

    public function testGuestCanSeeARoom()
    {
        $this->withoutExceptionHandling();

        $room = $this->createRoom();
        $user = $this->createUser();
        
        Sanctum::actingAs($user);

        $this->json('get', '/api/room/'.$room->id, ['Accept' => 'application/json'])
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
    }
}
