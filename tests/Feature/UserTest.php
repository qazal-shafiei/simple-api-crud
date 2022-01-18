<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use JWTAuth;

class UserTest extends TestCase
{
    use WithFaker;

    public function test_registration()
    {
        $response = $this->post('/api/register', [
           'name' => $this->faker->name,
           'email' => $this->faker->email,
           'password' => 123456,
           'password_confirmation' => 123456
        ]);

        $response->assertStatus(201);
    }

    public function test_login()
    {
        $user = User::factory()->create(['password' => Hash::make('123456')]);
        $response = $this->post('/api/login', [
            'email' => $user['email'],
            'password' => 123456
        ]);
        $response->assertStatus(200);
    }
}
