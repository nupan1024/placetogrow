<?php

namespace App\Http\Requests\Admin\Subscription;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSubscriptionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'description' => ['required', 'string'],
            'name' => ['required', 'string'],
            'billing_frequency' => ['required', 'string'],
            'status' => ['required', 'boolean'],
            'amount' => ['required', 'numeric'],
        ];
    }
}
