<?php

namespace App\Repository;

use App\Models\Cart;
use App\Models\Order;
use App\Models\User;
use App\Services\BillCalculation;
use Gloudemans\Shoppingcart\Facades\Cart as FacadesCart;

class OrderRepository
{
    public function saveOrder(object $data, $userId)
    {
        $bill = new BillCalculation($userId);
        $totalprice = $bill->calculateOrderPrice();
        $order = Order::create(
            [
            'user_id' =>  $userId,
            'total_price' => $totalprice,
            'payment_id' => $data->payment_id,
            'address_id' => $data->address_id,
            'delivery_at' => date('Y-m-d H:i:s', strtotime($data->delivery_at))
            ]
        );
        FacadesCart::instance('main')->erase($userId);
        return $order;
    }
}
