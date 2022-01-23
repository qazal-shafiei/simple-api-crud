<?php

namespace Tests\Feature;

use App\Models\Product;
use Dflydev\DotAccessData\Data;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
        $name = $this->faker->firstName;
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $this->authorization()['token'],
        ])->json('POST', '/api/products/', ['name' => $name]);

        $this->assertDatabaseHas('products', [
            'name' => $name,
        ]);
        $response->assertStatus(201);
    }

    public function test_update_product()
    {
        $name = $this->faker->firstName;
        $product = Product::first();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $this->authorization()['token'],
        ])->json('PUT', '/api/products/' . $product->id, ['name' => $name]);

        $this->assertDatabaseHas('products', [
            'name' => $name,
        ]);
        $response->assertStatus(200);
    }

    public function test_delete_product()
    {
        $product = Product::all()->last();
        $response = $this->call('DELETE', '/api/products/'. $product->id,
            ['HTTP_Authorization' => 'Bearer' . $this->authorization()['token']], []);

        $this->assertDatabaseMissing('users', [
            'name' => $product->name,
        ]);
        $response->assertStatus(200);
    }
}
