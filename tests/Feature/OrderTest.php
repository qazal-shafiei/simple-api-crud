<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use JWTAuth;

class OrderTest extends TestCase
{
    use WithFaker;

    /**
     * @return array
     */
    protected function authorization()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);
        return [
            'user' => $user,
            'token' => $token
        ];
    }

    /**
     * @return void
     */
   public function test_store_order()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $this->authorization()['token'],
        ])->json('POST','/api/orders');

        $response->assertStatus(201);
        $this->assertDatabaseHas('orders', [
            'user_id' => auth('api')->user()->getKey(),
        ]);
    }

    /**
     * @return void
     */
    public function test_show_order()
    {
        $order = Order::latest()->first();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer' . $this->authorization()['token'],
        ])->json('GET', '/api/orders/' . $order->id);

        $response->assertStatus(200);
    }

    public function test_delete_order()
    {
        $order = Order::latest()->first();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer' . $this->authorization()['token'],
        ])->json('DELETE', '/api/orders/' . $order->id);

        $this->assertDatabaseMissing('orders', [
            'user_id' => auth('api')->user()->getKey(),
        ]);
        $response->assertStatus(200);
    }
}
