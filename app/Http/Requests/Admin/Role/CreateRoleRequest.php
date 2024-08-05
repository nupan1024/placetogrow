<?php

namespace App\Http\Requests\Admin\Role;

use Illuminate\Foundation\Http\FormRequest;

class CreateRoleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'min:3', 'max:255', 'unique:roles'],
            'permissions' => ['required', 'array', 'min:1'],
        ];
    }
}
