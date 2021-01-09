<?php

namespace App\Services;

use App\Repositories\OrderRepository;
use App\Repositories\TagRepository;

class ReviewOrderService
{
    protected $order;
    protected $tag;

    public function __construct(OrderRepository $order, TagRepository $tag)
    {
        $this->order = $order;
        $this->tag = $tag;
    }

    public function getTags($rate)
    {
        $tags = $this->reviewOrder->getRateTags($rate);
        return $tags;
    }

    public function submitReview($data)
    {
        $order = $this->order->find($data->order);
        $this->order->addReview($order, $data->rate_id, $data->comment);
        $this->order->attachTags($order, $data->tag_id);
    }
}
