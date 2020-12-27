<?php

namespace App\Services;

use App\Models\Order;
use App\Repositories\CartRepository;
use App\Repositories\OrderRepository;
use Illuminate\Support\Facades\App;

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

    public function getCancellationReasons()
    {
        $cancellationreasonslist = $this->order->cancellationReasonsList(App::currentLocale());
        return $cancellationreasonslist;
    }

    public function cancelOrder($data)
    {
        $order = Order::findorfail($data->order_id);
        $cancelReason = $data->cancel_reason_id;
        $otherReason = $data->other_reason;
        $this->order->updateOrderStatus($order, 2);
        ($data->cancel_reason_id != null) ?
        $cancelledordered = $this->order->associateCancellationReasonToOrder($order, $cancelReason)
        : $cancelledordered = $this->order->associateOtherCancellationReasonToOrder($order, $otherReason);
        return $cancelledordered;
    }
}
