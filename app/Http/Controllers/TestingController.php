<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Cart;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\User;
use Carbon\Carbon;
use Facade\Ignition\Support\FakeComposer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestingController extends Controller
{
    public function testing()
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
//     $va = new FakeComposer();

//          dd($va->faker);

          // PaymentMethod::createMany([
          // ['name' => 'credit'],
          // ['name' => 'cash'],
          // ]);
        //   PaymentMethod::insert([
        //        ['name' => 'credit'],
        //        ['name' => 'cash'],
        //    ]);
        //    $date =  (Carbon::now()->addDays(2)->isFriday()) ? Carbon::now()->addDays(3) : Carbon::now()->addDays(2);
        //    dd($date);

        $dt = Carbon::parse('this friday');
        echo $dt;
    }
}
