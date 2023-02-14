<?php

namespace App\Http\Requests\Requests;

use App\Models\Customer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCustomerRequest extends FormRequest
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
            'email' => 'email|required|unique:customers',
            'full_name' => 'required|string|max:55',
            'address' => 'required|string',
            'type_identification' => 'required|' . Rule::in(Customer::identificationType()),
            'identification' => 'required|string|min:6'
        ];
    }
}
