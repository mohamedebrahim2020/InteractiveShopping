<?php

namespace App\Rules;

use App\Models\Order;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Lang;

class CheckReviewAuthorization implements Rule
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
        $order = Order::findorfail($value);
        return ($order->user_id == auth()->user()->id);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return Lang::get('validation.review_authorization');
    }
}
