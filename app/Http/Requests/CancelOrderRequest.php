<?php

namespace App\Http\Requests;

use App\Rules\CheckCancellationAbility;
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
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'order_id' => ['required','exists:orders,id', new CheckCancellationAbility()],
            'cancel_reason_id' => 'required_without:other_reason||nullable||exists:cancel_order_reasons,id',
            'other_reason' => 'required_without:cancel_reason_id',
        ];
    }
}
