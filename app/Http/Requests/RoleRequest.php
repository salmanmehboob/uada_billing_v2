<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Change this based on your authorization logic
    }
    public function rules()
    {
        $roleId = $this->route('role') ? $this->route('role')->id : null;

        return [
            'name' => 'required|string|max:255|unique:roles,name,' . $roleId,
            'permission' => 'required|array|min:1',
        ];
    }

}
