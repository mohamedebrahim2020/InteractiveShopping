<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderCancellationReason;
use Carbon\Carbon;

class OrderRepository
{
    public const ORDERSTATUS = 2 ;
    public function find($order)
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

    public function cancellationReasonsList()
    {
        $reasonlists = OrderCancellationReason::all();
        return $reasonlists;
    }

    public function updateOrderStatus(Order $order)
    {
        $order->order_status_id = self::ORDERSTATUS;
        $order->save();
        return $order;
    }

    public function associateCancellationReasonToOrder($order, $cancelId, $reason = null)
    {
        $this->find($order);
        $order = $this->find($order);
        $order->cancel_reason_id = $cancelId;
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
