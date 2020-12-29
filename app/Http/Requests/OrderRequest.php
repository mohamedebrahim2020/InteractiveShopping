<?php

namespace App\Http\Requests;

use App\Rules\CheckDefaultPayment;
use App\Rules\CheckUserCredit;
use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
           dd($this->route('order'));
        // return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'order' => 'required',
            // 'payment_id' => [ new CheckDefaultPayment(), new CheckUserCredit()],

        ];
    }
}
