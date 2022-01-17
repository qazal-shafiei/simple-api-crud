<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class ProductTest extends TestCase
{

    public function test_show_products()
    {
        $user = User::where('email', 'elvie40@gmail.com ')->first();
        $response = $this->actingAs($user)->get('/api/products');
        $response->assertStatus(200);
    }
}
