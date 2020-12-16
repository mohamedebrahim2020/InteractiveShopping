<?php

namespace App\Http\Controllers;

use App\Repository\OrderRepository;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $order;
    public function __construct(OrderRepository $order)
    {
        $this->order = $order;
    }
    public function submitOrder(Request $request)
    {
        $response = $this->order->saveOrder($request);
        return response()->json($response);
    }
}
