<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderCancellationReason;
use Carbon\Carbon;

class OrderRepository
{
    public function show($order)
    {
        $order = Order::findorfail($order);
        return $order;
    }

    public function list($user)
    {
        return $user->orders()->paginate(3, ["id", "total_price", "order_status_id"]);
    }
    public function saveOrder(object $data, $userId, $totalprice)
    {
        $order = Order::create(
            [
            'user_id' =>  $userId,
            'total_price' => $totalprice,
            'payment_id' => $data->payment_id,
            'address_id' => $data->address_id,
            'delivery_at' => Carbon::createFromFormat('Y-m-d H:i:s', $data->delivery_at),
            'order_status_id' => 1,
            ]
        );
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

    public function cancellationReasonsList($lang)
    {
        $reasonlists = OrderCancellationReason::all(['id','reason_desc_' . $lang]);
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

    public function addComment($order, $comment)
    {
        $order->comment = $comment;
        $order->save();
    }

    public function addRate($order, $rate)
    {
        $order->rate_id = $rate;
        $order->save();
    }

    public function attachTags($order, $tags)
    {
        foreach ($tags as $key => $value) {
            $order->tags()->attach($value);
        }
    }
}
