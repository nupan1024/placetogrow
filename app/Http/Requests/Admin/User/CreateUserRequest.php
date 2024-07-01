<?php

namespace App\Http\Requests\Admin\User;

use App\Domain\Users\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class CreateUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'role_id' => ['required', 'exists:roles,id'],
            'status' => ['required', 'boolean'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];
    }
}
