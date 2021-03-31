<?php

namespace Tests\Feature;

use App\Models\Room;
use App\Models\RoomImage;
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

    public function createRoomImage($room_id)
    {
        return RoomImage::create([
            'room_id' => $room_id,
            'img_name' => 'image1.jpg'
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

    public function testRequiredFieldsForCreateRoomImage()
    {
        $admin = $this->createAdmin();

        Sanctum::actingAs($admin);

        $this->json('post', '/api/roomImage', ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'room_id' => ['The room id field is required.'],
                    'img_name' => ['The img name field is required.']
                ]
            ]);
    }

    public function testSuccessfulUpdateRoomImage()
    {
        $room = $this->createRoom();
        $admin = $this->createAdmin();
        $roomImage = $this->createRoomImage($room->id); 
        $updateData = [
            'img_name' => 'image2.jpg'
        ];

        Sanctum::actingAs($admin);

        $this->json('patch', '/api/roomImage/'.$roomImage->id, $updateData, ['Accept' => 'application/json'])
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

        $this->assertDatabaseHas('room_images', $updateData);
        $this->assertDatabaseMissing('room_images', [
            'img_name' => 'image1.jpg'
        ]);
    }

    public function testOnlyAdminCanUpdateRoomImage()
    {
        $room = $this->createRoom();
        $user = $this->createUser();
        $roomImage = $this->createRoomImage($room->id);
        $updateData = [
            'img_name' => 'image2.jpg'
        ];

        Sanctum::actingAs($user);

        $this->json('patch', '/api/roomImage/'.$roomImage->id, $updateData, ['Accept' => 'application/json'])
            ->assertStatus(403)
            ->assertJson([
                'code' => 403,
                'message' => 'You are not allowed to do this action.'
            ]);

        $this->assertDatabaseMissing('room_images', $updateData);
    }

    public function testRequiredFieldsForUpdateRoomImage()
    {
        $room = $this->createRoom();
        $admin = $this->createAdmin();
        $roomImage = $this->createRoomImage($room->id);

        Sanctum::actingAs($admin);

        $this->json('patch', '/api/roomImage/'.$roomImage->id, ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'img_name' => ['The img name field is required.']
                ]
            ]);
        
        $this->assertDatabaseHas('room_images', [
            'room_id' => $roomImage->room_id,
            'img_name' => $roomImage->img_name
        ]);
    }
}
