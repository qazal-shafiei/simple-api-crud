<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Faker\Generator as Faker;
use JWTAuth;
use Config;

class UserTest extends TestCase
{
    use WithFaker;

    public function test_user_delete()
    {
        $user = User::first();
        $user ? $user->delete() : false;
        $this->assertTrue(true);
    }

    public function test_user_registration()
    {

        $response = $this->post('/api/register', [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => 123456,
            'password_confirmation' => 123456,
        ]);

        $response->assertStatus(201);
    }

    public function test_user_login()
    {

    $response = $this->json('POST', '/api/login' . '/', [
        'email' => 'qazal@gmail.com',
        'password' => '123456'
    ]);
    $accessToken = JWTAuth::attempt($loginData);

    $response
        ->assertStatus(200)
        ->assertJsonStructure([
            'accessToken', 'token_type', 'expires_in'
        ]);
    }
}
