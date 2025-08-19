<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TypeRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Change this based on your authorization logic
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:types,name,' . ($this->type->id ?? $this->type),

        ];
    }
}
