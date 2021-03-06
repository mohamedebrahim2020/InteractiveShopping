<?php

namespace App\Http\Controllers;

use App\Models\Cart as CartModel;
use App\Models\Product;
use App\Repositories\CartRepository;
use Illuminate\Http\Response;

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
        return response()->json($response, Response::HTTP_CREATED);
    }
    public function removeFromCart(Product $product)
    {
        $response = $this->cart->remove($product);
        return response()->json($response, Response::HTTP_OK);
    }

    public function increaseQuantity(product $product)
    {

        $response = $this->cart->increase($product);
        return response()->json($response, Response::HTTP_OK);
    }

    public function decreaseQuantity(Product $product)
    {
        $response = $this->cart->decrease($product);
        return response()->json($response, Response::HTTP_OK);
    }

    public function getCart($identifier)
    {
        $stored = CartModel::where('identifier', $identifier)->first();
        $storedContent = unserialize(data_get($stored, 'content'));
        $multiplied = $storedContent->map(
            function ($item, $key) {

                return ($item->subtotal + ($item->tax * $item->qty));
            }
        );
        dd($multiplied->sum());
        // dd($storedContent);
    }
}
