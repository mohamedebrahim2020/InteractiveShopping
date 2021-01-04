<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateOrder()
    {
        $this->withoutExceptionHandling();
        PaymentMethod::create(
            ['name' => 'credit'],
            ['name' => 'cash'],
        );
        Sanctum::actingAs(
            User::factory()->has(Address::factory()->count(3), 'addresses')->create(),
            ['*']
        );
        Product::factory()->create();
        $orderData = [
            "payment_id" => 1,
            "address_id" => 1,
            "delivery_at" => "21-01-06 08:00:01",
        ];

        $response = $this->postJson('/api/cart/products/1');
        $response->assertStatus(201);
        $response->assertSuccessful();
        $response = $this->postJson('/api/orders', $orderData, ['Accept' => 'application/json']);
        $response->assertStatus(201);
    }
}
