<?php

namespace App\Http\Requests\Payment;

use App\Support\Definitions\PaymentGateway;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
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
            'email' => ['required', 'email'],
            'type_document' => ['required'],
            'num_document' => ['required', 'numeric'],
            'value' => ['required'],
            'fields' => ['array'],
            'invoice_id' => ['numeric', 'exists:invoices,id'],
            'subscription_id' => ['numeric', 'exists:subscriptions,id'],
            'gateway' => ['required', Rule::in(PaymentGateway::toArray())],
        ];
    }
}
