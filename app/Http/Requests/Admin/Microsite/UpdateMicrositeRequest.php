<?php

namespace App\Http\Requests\Admin\Microsite;

use App\Support\Definitions\MicrositesTypes;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMicrositeRequest extends FormRequest
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
            'logo_path' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:2048'],
            'description' => ['required', 'string'],
            'currency_id' => ['required', 'exists:currencies,id'],
            'date_expire_pay' => [
                'exclude_unless:microsites_type_id,'.MicrositesTypes::INVOICE->value,
                'date', 'required', 'after:'.date('Y-m-d'),
            ],
            'status' => ['required', 'boolean'],
        ];
    }
}
