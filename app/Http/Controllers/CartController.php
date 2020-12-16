<?php

namespace App\Http\Controllers;

use App\Models\Cart as CartModel;
use App\Models\Product;
use App\Repository\CartRepository;
use Gloudemans\Shoppingcart\Facades\Cart;

class CartController extends Controller
{
    protected $cart;
    public function __construct(CartRepository $cart)
    {
        $this->cart = $cart;
    }
    public function addToCart(Product $product)
    {
        $response = $this->cart->add($product);
        return response()->json($response);
    }
    public function removeFromCart(Product $product)
    {
        $response = $this->cart->remove($product);
        return response()->json($response);
    }

    public function increaseQuantity(product $product)
    {
        
        $response = $this->cart->increase($product);
        return response()->json($response);
    }

    public function decreaseQuantity(Product $product)
    {
        $response = $this->cart->decrease($product);
        return response()->json($response);
    }

    public function getCart($identifier)
    {
        $data = Cart::instance('main')->restore($identifier);
        dd(Cart::instance('main')->content());
    }
}
