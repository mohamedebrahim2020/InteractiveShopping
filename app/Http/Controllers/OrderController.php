<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Repository\OrderRepository;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $order;
    public function __construct(OrderRepository $order)
    {
        $this->order = $order;
    }
    public function submitOrder(OrderRequest $request)
    {
        $response = $this->order->saveOrder($request, auth()->user()->id);
        return response()->json($response);
    }
}
