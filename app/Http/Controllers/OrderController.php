<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Repositories\OrderRepository;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $order;
    public function __construct(OrderService $order)
    {
        $this->order = $order;
    }
    public function submitOrder(OrderRequest $request)
    {
        $response = $this->order->placeOrder($request, auth()->user()->id);
        return response()->json($response);
    }
}