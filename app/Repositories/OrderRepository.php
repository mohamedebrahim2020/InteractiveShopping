<?php

namespace App\Repositories;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderCancellationReason;
use App\Models\User;
use App\Services\BillCalculation;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart as FacadesCart;

class OrderRepository
{
    public function saveOrder(object $data, $userId, $totalprice)
    {
        // $bill = new BillCalculation($userId);
        // $totalprice = $bill->calculateOrderPrice();
        $order = Order::create(
            [
            'user_id' =>  $userId,
            'total_price' => $totalprice,
            'payment_id' => $data->payment_id,
            'address_id' => $data->address_id,
            'delivery_at' => Carbon::createFromFormat('Y-m-d H:i:s', $data->delivery_at),
            // date('Y-m-d H:i:s', strtotime($data->delivery_at)),
            'order_status_id' => 1,
            ]
        );
       // FacadesCart::instance('main')->erase($userId);
        return $order;
    }

    public function saveOrderDetails(Order $order, $data)
    {
        foreach ($data as $key => $value) {
            $order->products()->attach(
                $value[0],
                ['unit_price' => $value[1],
                'quantity' => $value[2],
                'total_price' => ($value[1] * $value[2])
                ]
            );
        }
        return $order;
    }

    public function englishCancellationReasonsList()
    {
        $reasonlists = OrderCancellationReason::all(['id','reason_desc_en']);
        return $reasonlists;
    }

    public function arabicCancellationReasonsList()
    {
        $reasonlists = OrderCancellationReason::all(['id','reason_desc_ar']);
        return $reasonlists;
    }

    public function updateOrderStatus(Order $order, $id)
    {
        $order->order_status_id = $id;
        $order->save();
        return $order;
    }

    public function associateCancellationReasonToOrder(Order $order, $id)
    {
        $order->cancel_reason_id = $id;
        $order->save();
        return $order;
    }

    public function associateOtherCancellationReasonToOrder(Order $order, $reason)
    {
        $order->other_reason = $reason;
        $order->save();
        return $order;
    }
}
