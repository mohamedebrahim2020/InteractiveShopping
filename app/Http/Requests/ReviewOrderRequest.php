<?php

namespace App\Http\Requests;

use App\Models\Order;
use App\Rules\CheckOrderDelivered;
use App\Rules\CheckReviewAuthorization;
use Illuminate\Foundation\Http\FormRequest;

class ReviewOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $order = Order::findorfail($this->route('order'));
        return ($order->user_id == $this->user()->id && $order->order_status_id == 3);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'rate_id' => 'nullable||exists:rates,rank',
            'tag_id[]' => 'nullable||exists:tags,id',
            'comment' => 'nullable',
        ];
    }
}
