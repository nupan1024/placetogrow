<?php

namespace App\Http\Requests\Admin\Invoice;

use Illuminate\Foundation\Http\FormRequest;

class CreateInvoiceRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'microsite_id' => ['required', 'exists:microsites,id'],
            'user_id' => ['required', 'exists:users,id'],
            'description' => ['required', 'string'],
            'value' => ['required', 'numeric'],
        ];
    }
}
