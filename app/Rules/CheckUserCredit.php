<?php

namespace App\Rules;

use App\Models\User;
use App\Services\BillCalculation;
use Illuminate\Contracts\Validation\Rule;

class CheckUserCredit implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $orderprice = new BillCalculation(auth()->user()->id);
        $user = User::find(auth()->user()->id);
        if ($value == 1) {
            return $user->credit > $orderprice->calculateOrderPrice();
        } elseif ($value == null && $user->default_payment_id == 1) {
            return $user->credit > $orderprice->calculateOrderPrice();
        } else {
            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'no enough money in the credit';
    }
}
