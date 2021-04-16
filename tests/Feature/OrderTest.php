<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;
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

    public function createUsedRoom($userId)
    {
        return Room::create([
            'name' => 'room 1',
            'description' => 'a good room',
            'length' => 2,
            'width' => 1,
            'used_by' => $userId,
            'used_until' => Carbon::parse('2021-10-25')
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
        // $this->withoutExceptionHandling();

        $this->json('get', '/api/order', ['Accept' => 'application/json'])
            ->assertStatus(401)
            ->assertJson([
                'code' => 401,
                'message' => 'You are unauthenticated.'
            ]);
    }

    public function testSuccessfulCreateOrder()
    {
        $this->withExceptionHandling();

        $room = $this->createRoom();
        $user = $this->createUser();
        $data = [
            'room_id' => $room->id,
            'duration_in_month' => 1
        ];

        Sanctum::actingAs($user);

        $this->json('post', '/api/order', $data, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure([
                'code',
                'message',
                'data' => [
                    'id',
                    'user_id',
                    'room_id',
                    'duration_in_month',
                    'status',
                    'time_paid'
                ]
            ]);
        
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'room_id' => $room->id,
            'duration_in_month' => 1,
            'status' => 'unpaid',
            'time_paid' => null
        ]);
    }

    public function testGuestCannotCreateOrder()
    {
        $room = $this->createRoom();
        $data = [
            'room_id' => $room->id,
            'duration_in_month' => 1
        ];

        $this->json('post', '/api/order', $data, ['Accept' => 'application/json'])
            ->assertStatus(401)
            ->assertJson([
                'code' => 401,
                'message' => 'You are unauthenticated.'
            ]);

        $this->assertDatabaseMissing('orders', $data);
    }

    public function testRequiredFieldsForCreateOrder()
    {
        $user = $this->createUser();

        Sanctum::actingAs($user);

        $this->json('post', '/api/order', ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'room_id' => ['The room id field is required.'],
                    'duration_in_month' => ['The duration in month field is required.']
                ]
            ]);
    }

    public function testSuccessfulUpdateStatusOrder()
    {
        $admin = $this->createAdmin();
        $room = $this->createUsedRoom($admin->id);
        $order = Order::create([
            'user_id' => $admin->id,
            'room_id' => $room->id,
            'duration_in_month' => 1,
        ]);

        $updateData = [
            'status' => 'paid',
        ];

        Sanctum::actingAs($admin);

        $this->json('patch', '/api/order/'.$order->id, $updateData, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure([
                'code',
                'message',
                'data'
            ]);
        
        $this->assertDatabaseHas('orders', [
            'user_id' => $admin->id,
            'room_id' => $room->id,
            'duration_in_month' => 1,
            'status' => 'paid',
            'time_paid' => Carbon::now()
        ]);

        $this->assertDatabaseHas('rooms', [
            'id' => $room->id,
            'used_by' => $order->user_id,
            'used_until' => Carbon::parse($room->used_until)->addMonths($order->duration_in_month)
        ]);
    }

    public function testUserCannotUpdateStatusOrder()
    {
        $room = $this->createRoom();
        $user = $this->createUser();
        $order = Order::create([
            'user_id' => $user->id,
            'room_id' => $room->id,
            'duration_in_month' => 1,
        ]);

        $updateData = [
            'status' => 'paid',
        ];

        Sanctum::actingAs($user);

        $this->json('patch', '/api/order/'.$order->id, $updateData, ['Accept' => 'application/json'])
            ->assertStatus(403)
            ->assertJson([
                'code' => 403,
                'message' => 'You are not allowed to do this action.'
            ]);

        $this->assertDatabaseMissing('orders', [
            'status' => 'paid'
        ]);
    }

    public function testRequiredFieldForUpdateOrder()
    {
        $room = $this->createRoom();
        $admin = $this->createAdmin();
        $order = Order::create([
            'user_id' => $admin->id,
            'room_id' => $room->id,
            'duration_in_month' => 1,
        ]);

        Sanctum::actingAs($admin);

        $this->json('patch', '/api/order/'.$order->id, ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'status' => ['The status field is required.']
                ]
            ]);
    }

    public function testSuccessfulShowAnOrder()
    {
        $room = $this->createRoom();
        $user = $this->createUser();
        $order = Order::create([
            'user_id' => $user->id,
            'room_id' => $room->id,
            'duration_in_month' => 1,
        ]);

        Sanctum::actingAs($user);

        $this->json('get', '/api/order/'.$order->id, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure([
                'code',
                'message',
                'data' => [
                    'id',
                    'user_id',
                    'room_id',
                    'duration_in_month',
                    'status',
                    'time_paid'
                ]
            ]);
    }

    public function testCannotShowAnotherUserOrder()
    {
        $room = $this->createRoom();
        $user = $this->createUser();
        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('testing123'),
            'is_admin' => 1
        ]);
        $order = Order::create([
            'user_id' => $admin->id,
            'room_id' => $room->id,
            'duration_in_month' => 1,
        ]);

        Sanctum::actingAs($user);

        $this->json('get', '/api/order/'.$order->id, ['Accept' => 'application/json'])
            ->assertStatus(403)
            ->assertJson([
                'code' => 403,
                'message' => 'You are not allowed to do this action.'
            ]);
    }

    public function testGetOrderWrongId()
    {
        $room = $this->createRoom();
        $user = $this->createUser();
        $order = Order::create([
            'user_id' => $user->id,
            'room_id' => $room->id,
            'duration_in_month' => 1,
        ]);

        Sanctum::actingAs($user);

        $this->json('get', '/api/order/2', ['Accept' => 'application/json'])
            ->assertStatus(404)
            ->assertJsonStructure([
                'code',
                'message'
            ]);
    }
}
