<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Update this if you want to restrict access
    }

    public function rules()
    {
        $userId = $this->route('user') ? $this->route('user')->id : null;

        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId), // Unique except for edit
            ],
            'password' => $userId
                ? ['nullable', 'string', 'min:8', 'confirmed']
                : ['required', 'string', 'min:8', 'confirmed'],

            'role_id' => 'required|exists:roles,id',
        ];
    }

    public function messages()
    {
        return [
            'role_id.required' => 'Please select a role.',
            'role_id.exists' => 'Selected role is invalid.',
        ];
    }
}
