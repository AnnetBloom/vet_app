<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
{
    

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255', //regex:/^.+@.+$/i
            'city_id' => 'required|string|max:200',
            'cell_phone' => 'required|string|max:20|regex:/^\(\d{3}\)(\d{3}\-\d{2}\-\d{2})$/i', // (ХХХ)ХХХ-ХХ-ХХ   ^\(\d{3}\)[\d\- ]{7,10}$
        ];
    }
}
