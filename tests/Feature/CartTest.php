<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function testAdd()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );
        Product::factory()->create();
        $this->withoutExceptionHandling();
        $response = $this->postJson('/api/cart/products/1');
        $response->assertStatus(201);
        $response->assertSuccessful();
    }
    public function testRemove()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );
        Product::factory()->count(3)->create();
        $response = $this->deleteJson('/api/cart/products/1');
        $response->assertNotFound();
        $response->assertExactJson([
            "error" => "no cart ",
            "code" => 404
        ]);
        $response = $this->postJson('/api/cart/products/1');
        $response->assertCreated();
        $response = $this->postJson('/api/cart/products/2');
        $response->assertCreated();
        $response = $this->deleteJson('/api/cart/products/1');
        $response->assertOk();
        $response = $this->deleteJson('/api/cart/products/1');
        $response->assertNotFound();
        $response->assertExactJson([
            "error" => "this product is already not in the cart",
            "code" => 404
        ]);
    }
    public function testIncrement()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );
        Product::factory()->count(3)->create();
        $response = $this->postJson('/api/cart/products/1');
        $response->assertCreated();
        $response = $this->putJson('/api/cart/products/1/increment');
        $response->assertOk();
    }
    public function testDecrement()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );
        Product::factory()->count(3)->create();
        $response = $this->postJson('/api/cart/products/1');
        $response->assertCreated();
        $response = $this->putJson('/api/cart/products/1/decrement');
        $response->assertOk();
    }
}
