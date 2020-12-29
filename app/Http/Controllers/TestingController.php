<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Cart;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TestingController extends Controller
{
    public function testing(OrderRequest $request, $order)
    {
         //  dd($request->order);
    //     $stored = Cart::where('identifier', 1)->first();
    //     $storedContent = unserialize(data_get($stored, 'content'));
    //     $multiplied = $storedContent->map(
    //         function ($item, $key) {
    //             // return $item->id;
    //                return [$item->id,$item->price,$item->qty];
    //         }
    //     );
    //    // return $multiplied;
    //     foreach ($multiplied as $key => $value) {
    //         echo $value[2];
    //     }
    //     $order = Order::findorfail(22);
    //     //echo Carbon::($order->delivery_at);
    //     echo Carbon::parse($order->delivery_at)->format('l');
    //     echo Carbon::now()->format('l');
    }
}
