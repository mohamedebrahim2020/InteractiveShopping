<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Database\Factories\CartFactory;
use Gloudemans\Shoppingcart\Facades\Cart as FacadesCart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    /**
    * @test
    */
    public function successfull_add_product_to_cart()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );
        Product::factory()->create();
        $response = $this->postJson('/api/cart/products/1');
        $response->assertStatus(201);
        $response->assertSuccessful();
    }
    /**
    * @test
    */
    public function fail_to_add_not_found_model_product_to_cart()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );
        Product::factory()->create();
        $response = $this->postJson('/api/cart/products/2');
        $response->assertNotFound();
        $response->assertExactJson([
            'error' => "no model  product with this identifier",
            'code' => 404
        ]);
    }
    /**
    * @test
    */
    public function successfull_remove_product_from_cart()
    {
        $this->withoutExceptionHandling();
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );
         $product = Product::factory()->create();
        //  dd($product->id);
         $cartSession = FacadesCart::instance('main');
         $cartSession->add($product->id, $product->name, 1, $product->price)
         ->associate('\App\Models\Product');
         $cartSession->store(auth()->user()->id);
        $response = $this->deleteJson('/api/cart/products/' . $product->id);
        $response->assertOk();
    }
    /**
    * @test
    */
    public function fail_to_remove_product_not_in_cart()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );
        Product::factory(2)->create();
        $product = Product::findorfail(1);
         $cartSession = FacadesCart::instance('main');
         $cartSession->add($product->id, $product->name, 1, $product->price)
         ->associate('\App\Models\Product');
         $cartSession->store(auth()->user()->id);
         $productNotInCart = Product::findorfail(2);
         $response = $this->deleteJson('/api/cart/products/' . $productNotInCart->id);
         $response->assertNotFound();
         $response->assertExactJson([
            "error" => "this product is already not in the cart",
            "code" => 404
         ]);
    }
    public function testIncrement()
    {
        $this->withoutExceptionHandling();
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );
        Product::factory()->count(3)->create();
        $product = Product::findorfail(1);
        $cartSession = FacadesCart::instance('main');
        $cartSession->add($product->id, $product->name, 1, $product->price)
        ->associate('\App\Models\Product');
        $cartSession->store(auth()->user()->id);
        $response = $this->putJson('/api/cart/products/' . $product->id . '/increment');
        $response->assertOk();
    }
    public function testFailureIncrement()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );
        Product::factory()->count(3)->create();
        $product = Product::findorfail(1);
        $productNotInCart = Product::findorfail(2);
        $cartSession = FacadesCart::instance('main');
        $cartSession->add($product->id, $product->name, 1, $product->price)
        ->associate('\App\Models\Product');
        $cartSession->store(auth()->user()->id);
        $response = $this->putJson('/api/cart/products/' . $productNotInCart->id . '/increment');
        $response->assertNotFound();
        $response->assertExactJson([
            "error" => "this product is not in the cart",
            "code" => 404
        ]);
    }
    public function testDecrement()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );
        Product::factory()->count(3)->create();
        $product = Product::findorfail(1);
        $cartSession = FacadesCart::instance('main');
        $cartSession->add($product->id, $product->name, 1, $product->price)
        ->associate('\App\Models\Product');
        $cartSession->store(auth()->user()->id);
        $response = $this->putJson('/api/cart/products/' . $product->id . '/decrement');
        $response->assertOk();
    }
    public function testFailureDecrement()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );
        Product::factory()->count(3)->create();
        $product = Product::findorfail(1);
        $productNotInCart = Product::findorfail(2);
        $cartSession = FacadesCart::instance('main');
        $cartSession->add($product->id, $product->name, 1, $product->price)
        ->associate('\App\Models\Product');
        $cartSession->store(auth()->user()->id);
        $response = $this->putJson('/api/cart/products/' . $productNotInCart->id . '/decrement');
        $response->assertNotFound();
        $response->assertExactJson([
            "error" => "this product is already not in the cart",
            "code" => 404
        ]);
    }
}
