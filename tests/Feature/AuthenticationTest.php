<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
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

    public function testSuccessfulLogin()
    {
        $this->withoutExceptionHandling();
        $user = User::create([
            'name' => 'testing',
            'email' => 'testing@testing.com',
            'password' => Hash::make('testing123')
        ]);

        $loginData = [
            'email' => 'testing@testing.com',
            'password' => 'testing123'
        ];

        $this->json('post', '/api/login', $loginData, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure([
                'code',
                'message',
                'data' => [
                    'id',
                    'name',
                    'email'
                ],
                'token'
            ]);

        // $this->assertAuthenticated();
    }

    public function testRequiredFieldsForLogin()
    {
        // $this->withoutExceptionHandling();
        $this->json('post', '/api/login', ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'email' => ['The email field is required.'],
                    'password' => ['The password field is required.']
                ]
            ]);
    }

    public function testEmailAndPasswordMustMatch()
    {
        $this->withoutExceptionHandling();

        User::create([
            'name' => 'testing',
            'email' => 'testing@testing.com',
            'password' => 'testing123'
        ]);

        $loginData = [
            'email' => 'testing@testing.com',
            'password' => 'nottesting123'
        ];

        $this->json('post', '/api/login', $loginData, ['Accept' => 'application/json'])
            ->assertStatus(401)
            ->assertJson([
                'code' => 401,
                'message' => 'Email and password does not match.',
            ]);
    }

    public function testCanNotLoginWithUnregisteredCredential()
    {
        $this->withoutExceptionHandling();

        $loginData = [
            'email' => 'testing@testing.com',
            'password' => 'testing123'
        ];
        
        $this->json('post', '/api/login', $loginData, ['Accept' => 'application/json'])
            ->assertStatus(401)
            ->assertJson([
                'code' => 401,
                'message' => 'Email and password does not match.'
            ]);
    }

    public function testSuccessfulLogout()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $this->json('post', '/api/logout', ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure([
                'code',
                'message',
                'data'
            ]);
    }

    public function testOnlyAuthenticatedUserCanLogout()
    {
        // $this->withoutExceptionHandling();
        $this->json('post', '/api/logout', ['Accept' => 'application/json'])
            ->assertStatus(401)
            ->assertJson([
                'code' => 401,
                'message' => 'You are unauthenticated.'
            ]);
    }
}
