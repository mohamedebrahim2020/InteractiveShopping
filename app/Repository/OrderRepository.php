<?php

namespace App\Repository;

use App\Models\Cart;
use App\Models\Order;
use App\Models\User;
use Gloudemans\Shoppingcart\Facades\Cart as FacadesCart;

class OrderRepository
{
    public function saveOrder($data)
    {
        $totalprice = $this->calculateOrderPrice();
        if ($data->payment_id == null) {
            $data->payment_id = $this->DefaultPaymentMethod();
            if ($data->payment_id == 1) {
                $this->checkUserCredit($totalprice);
            }
        }
        $order = Order::create(
            [
            'user_id' =>  auth()->user()->id,
            'total_price' => $totalprice,
            'payment_id' => $data->payment_id,
            'address_id' => $data->address_id,
            'delivery_at' => date('Y-m-d H:i:s', strtotime($data->delivery_at))
            ]
        );
        $this->deleteCart();
        return $order;
    }

    public function calculateOrderPrice()
    {
        $stored = Cart::where('identifier', auth()->user()->id)->first();
        $storedContent = unserialize(data_get($stored, 'content'));
        $multiplied = $storedContent->map(
            function ($item, $key) {
                return ($item->subtotal + ($item->tax * $item->qty));
            }
        );
        return $multiplied->sum();
    }

    public function defaultPaymentMethod()
    {
        if (auth()->user()->default_payment_id == null) {
            abort(404, 'no default payment method');
        } else {
            return auth()->user()->default_payment_id;
        }
    }

    public function checkUserCredit($orderprice)
    {
        $user = User::find(auth()->user()->id);
        if ($user->credit < $orderprice) {
            abort(404, 'not enough credit');
        } else {
            $user->credit = $user->credit - $orderprice ;
            $user->save();
            return true;
        }
    }

    public function deleteCart()
    {
        FacadesCart::instance('main')->erase(auth()->user()->id);
    }
}
