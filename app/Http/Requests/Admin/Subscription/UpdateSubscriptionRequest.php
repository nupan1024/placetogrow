<?php

namespace App\Http\Requests\Admin\Subscription;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSubscriptionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'microsite_id' => ['required', 'exists:microsites,id'],
            'currency_id' => ['required', 'exists:currencies,id'],
            'description' => ['required', 'string'],
            'name' => ['required', 'string'],
            'billing_frequency' => ['required', 'string'],
            'status' => ['required', 'boolean'],
            'time_expire' => ['required', 'date', 'after:'.date('Y-m-d')],
            'amount' => ['required', 'numeric'],
        ];
    }
}
