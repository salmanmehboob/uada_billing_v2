<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Set to false if you want to restrict access
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone_no' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'sub_charges' => 'required|numeric|min:0',
            'invoice_prefix' => 'required|string|max:20',
            'receipt_footer' => 'nullable|string',
            'dept_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:800',
            'govt_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:800'
        ];
    }
}
