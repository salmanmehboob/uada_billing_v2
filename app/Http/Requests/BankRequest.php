<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BankRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Change this based on your authorization logic
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:banks,name,' . ($this->bank->id ?? $this->bank),
            'branch' => 'required',
            'account_no' => 'required',

        ];
    }
}
