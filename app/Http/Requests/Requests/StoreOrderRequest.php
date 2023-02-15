<?php

namespace App\Http\Requests\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
            'product_id' => 'sometimes|numeric|exists:products,id',
            'customer_id' => 'sometimes|numeric|exists:customers,id',
            'seller_id' => 'sometimes|numeric|exists:users,id'
        ];
    }
}
