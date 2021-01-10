<?php

namespace App\Http\Requests;

use App\Models\Order;
use App\Rules\CheckCancellationAbility;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class CancelOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $order = Order::findorfail($this->route('order'));
        return ($order->user_id == $this->user()->id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'cancel_reason_id' => ['required',
             'exists:cancel_order_reasons,id',
             new CheckCancellationAbility($this->route('order'))
            ],
            'other_reason' => 'required_if:cancel_reason_id,4',
        ];
    }
}
