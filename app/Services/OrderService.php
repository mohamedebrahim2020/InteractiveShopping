<?php

namespace App\Services;

use App\Repositories\CartRepository;
use App\Repositories\OrderRepository;
use Gloudemans\Shoppingcart\Facades\Cart;

class OrderService
{
    protected $order;
    public function __construct(OrderRepository $order)
    {
        $this->order = $order;
    }

    public function placeOrder(object $data, $userId)
    {
        $billobject = new BillCalculation($userId);
        $cartobject = new CartRepository();
        $orderdetails = $cartobject->cartDetails($userId);
        $totalprice = $billobject->calculateOrderPrice();
        $order = $this->order->saveOrder($data, $userId, $totalprice);
        $this->order->saveOrderDetails($order, $orderdetails);
        // Cart::instance('main')->erase($userId);
        return $order;
    }
}
