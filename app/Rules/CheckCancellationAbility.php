<?php

namespace App\Rules;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Lang;

class CheckCancellationAbility implements Rule
{
    public $id;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->id = $id;
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
        $order = Order::findorfail($this->id);
        $time = Carbon::now()->diffInMinutes($order->delivery_at);
        return ($time >= 1440);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return Lang::get('validation.cancellation_ability');
    }
}
