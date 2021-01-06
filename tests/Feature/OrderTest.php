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
        PaymentMethod::insert([
            ['name' => 'credit'],
            ['name' => 'cash'],
        ]);
        Sanctum::actingAs(
            User::factory()->hasAddresses(3)->create(),
            ['*']
        );
        Product::factory()->create();
        $orderData = [
            "payment_id" => 2,
            "address_id" => 1,
            "delivery_at" => "21-01-06 08:00:01",
        ];

        $response = $this->postJson('/api/cart/products/1');
        $response->assertStatus(201);
        $response->assertSuccessful();
        $response = $this->postJson('/api/orders', $orderData, ['Accept' => 'application/json']);
        $response->assertStatus(201);
    }
    public function testFailureCreatedOrder()
    {
        PaymentMethod::insert([
            ['name' => 'credit'],
            ['name' => 'cash'],
        ]);
        Sanctum::actingAs(
            User::factory()->hasAddresses(3)->create(),
            ['*']
        );
        Product::factory()->create();
        $orderData = [
            "payment_id" => 1,
            "address_id" => "",
            "delivery_at" => "21-01-08 08:00:01",
        ];

        $response = $this->postJson('/api/cart/products/1');
        $response->assertStatus(201);
        $response->assertSuccessful();
        $response = $this->postJson('/api/orders', $orderData, ['Accept' => 'application/json']);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['payment_id', 'address_id', 'delivery_at']);
        $response->assertExactJson([
            "message" =>  "The given data was invalid.",
            "errors" => [
                "address_id" => ["The address id field is required."],
                "delivery_at" => ["not allowed to deliver products on holiday"],
                "payment_id" => ["not enough credit"]
            ]
        ]);
    }
    public function testCancelOrder()
    {
        $this->withoutExceptionHandling();
        PaymentMethod::insert([
            ['name' => 'credit'],
            ['name' => 'cash'],
        ]);
        OrderStatus::insert([
            ['status_name_en' => 'ordered','status_name_ar' => 'مطلوب'],
            ['status_name_en' => 'cancelled','status_name_ar' => 'ملغي'],
        ]);
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
    public function testFailureCancel()
    {
        PaymentMethod::insert([
            ['name' => 'credit'],
            ['name' => 'cash'],
        ]);
        OrderStatus::insert([
            ['status_name_en' => 'ordered','status_name_ar' => 'مطلوب'],
            ['status_name_en' => 'cancelled','status_name_ar' => 'ملغي'],
        ]);
        OrderCancellationReason::create([
            'reason_desc_en' => 'i changed my mind',
            'reason_desc_ar' => 'غيرت رأيي',
        ]);
        Sanctum::actingAs(
            User::factory()->hasOrders(1, ['delivery_at' => Carbon::now()->addHours(20)])->create(),
            ['*']
        );
        $orderCancelData = [
            "cancel_reason_id" => 1,
        ];
        $response = $this->postJson('/api/order/1/cancel', $orderCancelData);
        $response->assertStatus(422);
        $response->assertExactJson([
            "message" =>  "The given data was invalid.",
            "errors" => [
                "cancel_reason_id" => ["you cannot cancel the order before 24hrs from delivery date"],
            ]
        ]);
    }
    public function testGetOrder()
    {
        $this->withoutExceptionHandling();
        PaymentMethod::insert([
            ['name' => 'credit'],
            ['name' => 'cash'],
        ]);
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

    public function testGetCancellationReasons()
    {
        OrderCancellationReason::create([
            'reason_desc_en' => 'i changed my mind',
            'reason_desc_ar' => 'غيرت رأيي',
        ]);
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );
        $response = $this->getJson('/api/order/cancel/reasons', ['Accept' => 'application/json']);
        $response->assertOk();
        $response->assertExactJson([[
            "id" => 1,
            "cancel_reason" => 'i changed my mind'
        ]]);
    }
}
