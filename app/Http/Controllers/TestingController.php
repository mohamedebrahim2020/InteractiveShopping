<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;

class TestingController extends Controller
{
    public function testing()
    {
        $stored = Cart::where('identifier', 1)->first();
        $storedContent = unserialize(data_get($stored, 'content'));
        $multiplied = $storedContent->map(
            function ($item, $key) {
                // return $item->id;
                   return [$item->id,$item->price,$item->qty];
            }
        );
       // return $multiplied;
        foreach ($multiplied as $key => $value) {
            echo $value[2];
        }
    }
}
