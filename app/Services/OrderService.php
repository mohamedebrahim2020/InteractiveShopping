<?php

namespace App\Services;

use App\Http\Resources\Order as ResourcesOrder;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\User;
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

    public function get($order)
    {
        $order = $this->order->find($order);
        return new OrderResource($order);
    }

    public function listOrders()
    {
        $user = User::findorfail(auth()->user()->id);
        $orders = $this->order->list($user);
        return $orders;
    }
    public function placeOrder(object $data, $identifier)
    {
        $billobject = new BillCalculation($identifier);
        $cartobject = new CartRepository();
        $orderdetails = $cartobject->cartDetails($identifier);
        $totalprice = $billobject->calculateOrderPrice();
        $order = $this->order->saveOrder($data, $identifier, $totalprice);
        $this->order->saveOrderDetails($order, $orderdetails);
        return $order;
    }

    public function getCancellationReasons()
    {
        $cancellationreasonslist = $this->order->cancellationReasonsList(App::currentLocale());
        return $cancellationreasonslist;
    }

    public function cancelOrder($data)
    {
        $this->order->updateOrderStatus($data->order);
        $cancelledordered = $this->order
        ->associateCancellationReasonToOrder($data->order, $data->cancel_reason_id, $data->other_reason);
        return $cancelledordered;
    }
}
