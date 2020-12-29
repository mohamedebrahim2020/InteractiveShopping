<?php

namespace App\Http\Controllers;

use App\Http\Requests\CancelOrderRequest;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\CancellationReasonResource;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    protected $order;
    public function __construct(OrderService $order)
    {
        $this->order = $order;
    }
    public function index()
    {
        $orders = $this->order->listOrders();
        return response()->json($orders);
    }
    public function store(OrderRequest $request)
    {
        $response = $this->order->placeOrder($request, auth()->user()->id);
        return response()->json($response);
    }
    public function listCancellationReasons()
    {
        $response = $this->order->getCancellationReasons();
        return response()->json(CancellationReasonResource::collection($response), Response::HTTP_OK);
    }
    public function cancel(CancelOrderRequest $request)
    {
        $response = $this->order->cancelOrder($request);
        return response()->json($response);
    }
    public function show($order)
    {
        $order = $this->order->get($order);
        return response()->json($order, Response::HTTP_OK);
    }
}
