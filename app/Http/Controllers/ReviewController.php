<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewOrderRequest;
use App\Models\Order;
use App\Models\Rate;
use App\Services\ReviewOrderService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ReviewController extends Controller
{
    protected $reviewOrder;
    public function __construct(ReviewOrderService $reviewOrder)
    {
        $this->reviewOrder = $reviewOrder;
    }
    public function getRateTags(Rate $rate)
    {
        $response = $this->reviewOrder->getTags($rate);
        return response()->json($response);
    }
    public function reviewOrder(Order $order, ReviewOrderRequest $request)
    {
        $this->reviewOrder->submitReview($order, $request);
        return response()->json(null, Response::HTTP_CREATED);
    }
}
