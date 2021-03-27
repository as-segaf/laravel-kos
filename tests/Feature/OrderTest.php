<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class OrderTest extends TestCase
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

    public function createOrder($userId, $roomId)
    {
        return Order::create([
            'user_id' => $userId,
            'room_id' => $roomId,
            'duration_in_month' => 1,
            'status' => 'unpaid',
            'time_paid' => '' 
        ]);
    }

    public function testUserCanSeeTheirOrders()
    {
        $this->withoutExceptionHandling();
        
        $room = $this->createRoom();
        $user = $this->createUser();
        $order = $this->createOrder($user->id, $room->id);

        Sanctum::actingAs($user);

        $this->json('get', '/api/order', ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure([
                'code',
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'user_id',
                        'room_id',
                        'duration_in_month',
                        'status',
                        'time_paid'
                    ]
                ]
            ])
            ->assertJson([
                'code' => 200,
                'message' => 'success',
                'data' => [
                    [
                        'id' => $order->id,
                        'user_id' => $order->user_id,
                        'room_id' => $order->room_id,
                        'duration_in_month' => $order->duration_in_month,
                        'status' => $order->status,
                        'time_paid' => $order->time_paid
                    ]
                ]
            ]);
    }

    public function testGuestCannotSeeOrders()
    {
        $this->withoutExceptionHandling();

        $this->json('get', '/api/order', ['Accept' => 'application/json'])
            ->assertStatus(401)
            ->assertJson([
                'code' => 401,
                'message' => 'You are unauthenticated.'
            ]);
    }
}
