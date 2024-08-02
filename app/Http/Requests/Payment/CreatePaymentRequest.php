<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\In;

class CreatePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int|In|string>>
     */
    public function rules(): array
    {
        return [
            'microsite_id' => ['required', 'numeric', 'exists:microsites,id'],
            'name' => ['required', 'string'],
            'currency' => ['required', 'string'],
            'description' => ['required', 'string'],
            'email' => ['required', 'email'],
            'type_document' => ['required'],
            'num_document' => ['required'],
            'value' => ['required'],
            'fields' => ['array'],
        ];
    }
}
