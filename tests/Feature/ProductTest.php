<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use JWTAuth;

class ProductTest extends TestCase
{
    use WithFaker;
    protected function authorization()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);
        return [
            'user' => $user,
            'token' => $token
        ];
    }
    public function test_show_products()
    {
        $response = $this->call('GET','/api/products',
            [], [],[],['HTTP_Authorization' => 'Bearer ' . $this->authorization()['token']],[]);
        $response->assertStatus(200);
    }

    public function test_store_product()
    {
        $response = $this->call('POST','/api/products',
            ["name" => $this->faker->firstName],[],[],['HTTP_Authorization' => 'Bearer ' . $this->authorization()['token']],[]);
        $response->assertStatus(201);
    }

    public function test_update_product()
    {
        $response = $this->call('PUT','/api/products/27',
            ["name" => $this->faker->firstName],[],[],['HTTP_Authorization' => 'Bearer ' . $this->authorization()['token']],[]);
        $response->assertStatus(200);
    }

    public function test_delete_product()
    {
        $product = Product::all()->last();
        $response = $this->call('DELETE', '/api/products/'. $product->id,
            [], [], [], ['HTTP_Authorization' => 'Bearer' . $this->authorization()['token']], []);
        $response->assertStatus(200);
    }
}
