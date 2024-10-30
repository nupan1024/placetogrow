<?php

namespace App\Http\Requests\Admin\Microsite;

use Illuminate\Foundation\Http\FormRequest;

class CreateMicrositeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'min:3', 'max:255'],
            'microsites_type_id' => ['required', 'exists:microsites_types,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'logo_path' => [
                'required', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:2048',
            ],
            'description' => ['required', 'string'],
            'currency_id' => ['required', 'exists:currencies,id'],
            'status' => ['required', 'boolean'],
            'fields' => ['array'],
        ];
    }
}
