<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function testSuccessfulRegistration()
    {
        $this->withoutExceptionHandling();
        $userData = [
            'name' => 'testing',
            'email' => 'testing@testing.com',
            'password' => 'testing123'
        ];
        $this->json('post', '/api/register', $userData, ['Accept' => 'Application/json'])
            ->assertStatus(200)
            ->assertJsonStructure([
                'code',
                'message',
                'data' => [
                    'id',
                    'name',
                    'email',
                ]
            ]);
        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseHas('users', [
            'name' => 'testing',
            'email' => 'testing@testing.com',
        ]);
    }

    public function testRequiredFieldsForRegistration()
    {
        // $this->withoutExceptionHandling();
        $this->json('post', '/api/register', ['Accept' => 'Application/json'])
            ->assertStatus(422)
            ->assertJson([
                'message' => "The given data was invalid.",
                'errors' => [
                    'name' => ['The name field is required.'],
                    'email' => ['The email field is required.'],
                    'password' => ['The password field is required.'],
                ]
            ]);
    }
}
