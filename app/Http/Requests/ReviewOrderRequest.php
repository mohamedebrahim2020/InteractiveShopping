<?php

namespace App\Http\Requests;

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
            // 'order_id' => [ new CheckReviewAuthorization(), new CheckOrderDelivered()],
            'rate_id' => 'nullable||exists:rates,rank',
            'tag_id[]' => 'nullable||exists:tags,id',
            'comment' => 'nullable',
        ];
    }
}
