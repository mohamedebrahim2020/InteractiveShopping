<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\OrderCancellationReason;
use App\Models\OrderStatus;
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
            User::factory()->hasAddresses(3)->create(),
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

    public function testCancelOrder()
    {
        $this->withoutExceptionHandling();
        PaymentMethod::create(
            ['name' => 'credit'],
            ['name' => 'cash'],
        );
        OrderStatus::create(
            ['status_name_en' => 'ordered','status_name_ar' => 'مطلوب'],
            ['status_name_en' => 'cancelled','status_name_ar' => 'ملغي'],
        );
        OrderCancellationReason::create([
            'reason_desc_en' => 'i changed my mind',
            'reason_desc_ar' => 'غيرت رأيي',
        ]);
        Sanctum::actingAs(
            User::factory()->hasOrders(1)->create(),
            ['*']
        );
        $orderCancelData = [
            "cancel_reason_id" => 1,
        ];
        $response = $this->postJson('/api/order/1/cancel', $orderCancelData);
        $response->assertStatus(200);
    }

    public function testGetOrder()
    {
        $this->withoutExceptionHandling();
        PaymentMethod::create(
            ['name' => 'credit'],
            ['name' => 'cash'],
        );
        OrderStatus::create(
            ['status_name_en' => 'ordered','status_name_ar' => 'مطلوب'],
            ['status_name_en' => 'cancelled','status_name_ar' => 'ملغي'],
        );
        Sanctum::actingAs(
            User::factory()->hasOrders(5)->create(),
            ['*']
        );
        $response = $this->getJson('/api/orders');
        $this->assertAuthenticated();
        $response->assertOk();
    }
}
