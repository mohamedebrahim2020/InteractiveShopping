<?php

namespace App\Services;

use App\Repositories\OrderRepository;
use App\Repositories\ReviewOrderRepository;

class ReviewOrderService
{
    protected $order;
    protected $reviewOrder;

    public function __construct(OrderRepository $order, ReviewOrderRepository $reviewOrder)
    {
        $this->order = $order;
        $this->reviewOrder = $reviewOrder;
    }

    public function getTags($rate)
    {
        $tags = $this->reviewOrder->getRateTags($rate);
        return $tags;
    }

    public function submitReview($order, $data)
    {
        if ($data->comment != null) {
            $this->order->addComment($order, $data->comment);
        }
        $this->order->addRate($order, $data->rate_id);
        $this->order->attachTags($order, $data->tag_id);
    }

}