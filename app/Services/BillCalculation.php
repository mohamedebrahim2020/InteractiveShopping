<?php

namespace App\Services;

use App\Models\Cart;

class BillCalculation
{
    protected $userId;
    public function __construct($id)
    {
        $this->userId = $id;
    }

    public function calculateOrderPrice()
    {
        $stored = Cart::where('identifier', $this->userId)->first();
        $storedContent = unserialize(data_get($stored, 'content'));
        $multiplied = $storedContent->map(
            function ($item, $key) {
                return ($item->subtotal + ($item->tax * $item->qty));
            }
        );
        return $multiplied->sum();
    }
}
