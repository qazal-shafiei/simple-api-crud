<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

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
        $response = $this->post('api/login', [
            'email' => 'emaill@email.com',
            'password' => 123456
        ]);
        $response->assertStatus(200);
    }
}
