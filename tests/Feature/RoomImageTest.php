<?php

namespace Tests\Feature;

use App\Models\Room;
use App\Models\RoomImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
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
            'price_per_month' => 500000
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
            'img_name' => 'room_image_image1-'.time().'.'.'jpg'
        ]);
    }

    public function testSuccessfulAddRoomImage()
    {
        $room = $this->createRoom();
        $admin = $this->createAdmin();
        $file = UploadedFile::fake()->image('image1.png');
        $data = [
            'room_id' => $room->id,
            'img_name' => $file
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

        $this->assertFileExists(public_path('images/room_image_image1-'.time().'.'.'png'));
        $this->assertDatabaseHas('room_images', [
            'room_id' => $room->id,
            'img_name' => 'room_image_image1-'.time().'.'.'png'
        ]);
    }

    public function testOnlyAdminCanAddRoomImage()
    {
        $room = $this->createRoom();
        $user = $this->createUser();
        $file = UploadedFile::fake()->image('image2.png');
        $data = [
            'room_id' => $room->id,
            'img_name' => $file
        ];

        Sanctum::actingAs($user);

        $this->json('post', '/api/roomImage', $data, ['Accept' => 'application.json'])
            ->assertStatus(403)
            ->assertJson([
                'code' => 403,
                'message' => 'You are not allowed to do this action.'
            ]);

        $this->assertFileDoesNotExist(public_path('images/room_image_image2-'.time().'.'.'png'));
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
        $file = UploadedFile::fake()->image('image1.png');
        $updateData = [
            'oldFile' => $roomImage->img_name,
            'img_name' => $file
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

        $this->assertFileExists(public_path('images/room_image_image1-'.time().'.'.'png'));
        $this->assertFileDoesNotExist(public_path('images/room_image_image1-'.time().'.'.'jpg'));
        $this->assertDatabaseHas('room_images', [
            'img_name' => 'room_image_image1-'.time().'.'.'png'
        ]);
        $this->assertDatabaseMissing('room_images', [
            'img_name' => $roomImage->img_name
        ]);
    }

    public function testOnlyAdminCanUpdateRoomImage()
    {
        $room = $this->createRoom();
        $user = $this->createUser();
        $roomImage = $this->createRoomImage($room->id);
        $file = UploadedFile::fake()->image('image1.png');
        $updateData = [
            'oldFile' => $roomImage->img_name,
            'img_name' => $file
        ];

        Sanctum::actingAs($user);

        $this->json('patch', '/api/roomImage/'.$roomImage->id, $updateData, ['Accept' => 'application/json'])
            ->assertStatus(403)
            ->assertJson([
                'code' => 403,
                'message' => 'You are not allowed to do this action.'
            ]);

        $this->assertDatabaseMissing('room_images', [
            'img_name' => 'room_image_image1-'.time().'.'.'png'
        ]);
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
                    'oldFile' => ['The old file field is required.'],
                    'img_name' => ['The img name field is required.']
                ]
            ]);
        
        $this->assertDatabaseHas('room_images', [
            'room_id' => $roomImage->room_id,
            'img_name' => $roomImage->img_name
        ]);
    }

    public function testSuccessfulDeleteRoomImage()
    {
        $room = $this->createRoom();
        $admin = $this->createAdmin();
        $roomImage = $this->createRoomImage($room->id);

        Sanctum::actingAs($admin);

        $this->json('delete', '/api/roomImage/'.$roomImage->id, ['Accept' => 'application/json'])
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

        $this->assertDatabaseCount('room_images', 0);
    }

    public function testOnlyAdminCanDeleteRoomImage()
    {
        $room = $this->createRoom();
        $user = $this->createUser();
        $roomImage = $this->createRoomImage($room->id);

        Sanctum::actingAs($user);

        $this->json('delete', '/api/roomImage/'.$roomImage->id, ['Accept' => 'application/json'])
            ->assertStatus(403)
            ->assertJson([
                'code' => 403,
                'message' => 'You are not allowed to do this action.'
            ]);

        $this->assertDatabaseCount('room_images', 1);
    }
}
