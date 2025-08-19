<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AlloteeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'plot_no'             => 'required|string|max:255',
            'name'                => 'required|string|max:255',
            'guardian_name'       => 'nullable|string|max:255',
            'email'               => 'nullable|email|max:255',
            'phone_no'            => 'nullable|string|max:255',
            'account_no'          => 'nullable|string|max:255',
            'contact_person_name' => 'nullable|string|max:255',
            'address'             => 'nullable|string',
            'sector_id'           => 'nullable|integer|exists:sectors,id',
            'size_id'             => 'nullable|integer|exists:sizes,id',
            'type_id'             => 'nullable|integer|exists:types,id',
            'is_active'           => 'nullable|integer|in:0,1',
            'arrears'             => 'nullable|string|max:255',
        ];
    }
}

