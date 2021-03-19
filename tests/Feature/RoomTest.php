<?php

namespace Tests\Feature;

use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
}
