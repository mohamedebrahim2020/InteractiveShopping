<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\OrderCancellationReasonSeeder;
use Database\Seeders\OrderStatusSeeder;
use Database\Seeders\PaymentMethodSeeder;
use Database\Seeders\RateSeeder;
use Database\Seeders\TagCategorySeeder;
use Database\Seeders\TagSeeder;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function setup() : void 
    {
        parent::setUp();
        $this->seed(PaymentMethodSeeder::class);
        $this->seed(OrderStatusSeeder::class);
        $this->seed(OrderCancellationReasonSeeder::class);
        $this->seed(TagCategorySeeder::class);
        $this->seed(TagSeeder::class);
        $this->seed(RateSeeder::class);

    }
    /** @test */
    public function successful_create_order()
    {
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
        $product = Product::findorfail(1);
        $cartSession = Cart::instance('main');
        $cartSession->add($product->id, $product->name, 1, $product->price)
        ->associate('\App\Models\Product');
        $cartSession->store(auth()->user()->id);
        $response = $this->postJson('/api/orders', $orderData, ['Accept' => 'application/json']);
        $response->assertStatus(201);
    }
    /** @test */
    public function not_enough_credit_to_place_order()
    {
        Sanctum::actingAs(
            User::factory()->hasAddresses(3)->create(),
            ['*']
        );
        Product::factory()->create();
        $date = (Carbon::now()->addDays(2)->isFriday()) ? Carbon::now()->addDays(3) : Carbon::now()->addDays(2);
        $orderData = [
            "payment_id" => 1,
            "address_id" => 1,
            "delivery_at" => $date,
        ];
        $product = Product::findorfail(1);
        $cartSession = Cart::instance('main');
        $cartSession->add($product->id, $product->name, 1, $product->price)
        ->associate('\App\Models\Product');
        $cartSession->store(auth()->user()->id);
        $response = $this->postJson('/api/orders', $orderData, ['Accept' => 'application/json']);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['payment_id']);
        $response->assertExactJson([
            "message" =>  "The given data was invalid.",
            "errors" => [
                "payment_id" => ["not enough credit"]
            ]
        ]);
    }
    /** @test */
    public function delivery_date_cannot_be_in_friday()
    {
            Sanctum::actingAs(
                User::factory()->hasAddresses(3)->create(),
                ['*']
            );
            Product::factory()->create();
            $orderData = [
                "payment_id" => 2,
                "address_id" => 1,
                "delivery_at" => Carbon::parse('this friday'),
            ];
            $product = Product::findorfail(1);
            $cartSession = Cart::instance('main');
            $cartSession->add($product->id, $product->name, 1, $product->price)
            ->associate('\App\Models\Product');
            $cartSession->store(auth()->user()->id);
            $response = $this->postJson('/api/orders', $orderData, ['Accept' => 'application/json']);
            $response->assertStatus(422);
            $response->assertJsonValidationErrors(['delivery_at']);
            $response->assertExactJson([
                "message" =>  "The given data was invalid.",
                "errors" => [
                    "delivery_at" => ["not allowed to deliver products on holiday"],
                ]
            ]);
    }
    /** @test */
    public function successful_cancel_order()
    {
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
    /** @test */
    public function fail_to_cancel_order_before_one_day_from_delivery_date()
    {
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
    /** @test */
    public function successfull_get_orders()
    {
        Sanctum::actingAs(
            User::factory()->hasOrders(5)->create(),
            ['*']
        );
        $response = $this->getJson('/api/orders');
        $this->assertAuthenticated();
        $response->assertOk();
    }
    /** @test */
    public function successfull_get_cancellation_reasons()
    {
        $this->seed(OrderCancellationReasonSeeder::class);
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );
        $response = $this->getJson('/api/order/cancel/reasons', ['Accept' => 'application/json']);
        $response->assertOk();
        $response->assertJsonStructure([[
            "id",
            "cancel_reason"
        ]]);
    }
    /** @test */
    public function successful_review_order()
    {
        Sanctum::actingAs(
            User::factory()->hasOrders(1, ['order_status_id' => 3])->create(),
            ['*']
        );
        $reviewOrderData = [
            "rate_id" => 1,
            "tag_id" => [1,2],
            "comment" => "not a good product",
        ];
        $response = $this->postJson('/api/order/1/review', $reviewOrderData, ['Accept' => 'application/json']);
        $response->assertCreated();
    }
}
