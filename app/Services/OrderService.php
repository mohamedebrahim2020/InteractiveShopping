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

    public function placeOrder(object $data, $identifier)
    {
        $billobject = new BillCalculation($identifier);
        $cartobject = new CartRepository();
        $orderdetails = $cartobject->cartDetails($identifier);
        $totalprice = $billobject->calculateOrderPrice();
        $order = $this->order->saveOrder($data, $identifier, $totalprice);
        $this->order->saveOrderDetails($order, $orderdetails);
        // Cart::instance('main')->erase($userId);
        return $order;
    }

    public function getCancellationReasons($reasonlanguage)
    {
        $cancellationreasonslist = $this->selectCancellationReasonsLang($reasonlanguage);
        return $cancellationreasonslist;
    }

    public function selectCancellationReasonsLang($reasonlanguage)
    {
        if ($reasonlanguage == "ar") {
            $cancellationreasonslist = $this->order->arabicCancellationReasonsList();
        } else {
            $cancellationreasonslist = $this->order->englishCancellationReasonsList();
        }
        return $cancellationreasonslist;
    }
}
