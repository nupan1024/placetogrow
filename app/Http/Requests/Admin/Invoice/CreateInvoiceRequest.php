<?php

namespace App\Http\Requests\Admin\Invoice;

use Carbon\Carbon;
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
            'date_expire_pay' => [
                'date', 'required', 'after_or_equal:'. Carbon::now()->addDays(3)->toDateString(),
            ],
        ];
    }
}
