<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CheckDefaultPayment implements Rule
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
        if ($value == null && auth()->user()->default_payment_id == null) {
            return false;
        } elseif ($value == null && auth()->user()->default_payment_id == 1) {
            $value = 1;
            return $value;
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
        return 'no default payment method';
    }
}
