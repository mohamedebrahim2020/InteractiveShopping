<?php

namespace App\Http\Controllers;

use App\Models\Cart as CartModel;
use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;

class CartController extends Controller
{
    public function addToCart($product)
    {
        $cart = new CartModel($product);
        $response = $cart->add();
        return response()->json($response);
    }
    public function removeFromCart($product)
    {
        $cart = new CartModel($product);
        $response = $cart->remove();
        return response()->json($response);
    }

    public function increaseQuantity($product)
    {
        $cart = new CartModel($product);
        $response = $cart->increase();
        return response()->json($response);
    }

    public function decreaseQuantity($product)
    {
        $cart = new CartModel($product);
        $response = $cart->decrease();
        return response()->json($response);
    }

    public function getCart($identifier)
    {
        $data = Cart::instance('main')->restore($identifier);
        dd(Cart::instance('main')->content());
    }
}
