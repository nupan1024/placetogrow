<?php

namespace App\Http\Requests\Admin\User;

use App\Domain\Users\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function rules(): array
    {
        $id = explode('/', $this->path())[1];

        return [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($id)],
            'role_id' => ['required', 'exists:roles,id'],
            'status' => ['required', 'boolean'],
        ];
    }
}
