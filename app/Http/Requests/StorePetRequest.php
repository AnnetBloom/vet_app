<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePetRequest extends FormRequest
{
   

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'alias' => 'required|string|max:255',
            'breed_id' => 'required|integer|min:0|not_in:0',
            'type_id' => [
                'required',
                'integer',
                Rule::in([1,2,3,4,5,6]),
            ],
            'owner_id' => 'required',
        ];
    }
}
