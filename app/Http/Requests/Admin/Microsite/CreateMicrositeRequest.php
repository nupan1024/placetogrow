<?php

namespace App\Http\Requests\Admin\Microsite;

use Illuminate\Foundation\Http\FormRequest;

class CreateMicrositeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'min:3', 'max:255'],
            'microsites_type_id' => ['required', 'exists:microsites_types,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'logo_path' => ['required', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:2048'],
            'description' => ['required', 'string'],
            'currency_id' => ['required', 'exists:currencies,id'],
            'date_expire_pay' => ['required'],
            'status' => ['required', 'boolean'],
        ];
    }
}
