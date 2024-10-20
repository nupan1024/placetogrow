<?php

namespace App\Http\Requests\Admin\Invoice;

use Closure;
use Illuminate\Foundation\Http\FormRequest;

class UpdateInvoiceRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'microsite_id' => ['required', 'exists:microsites,id'],
            'user_id' => ['required', 'exists:users,id'],
            'description' => ['required', 'string'],
            'value' => ['required', 'numeric'],
            'date_expire_pay' => [
                'date',
                'required',
                'after_or_equal:'. date('Y-m-d'),
                function (string $attribute, mixed $value, Closure $fail) {
                    if ($value !== $this->invoice->date_expire_pay && $value < $this->invoice->date_expire_pay) {
                        $fail(__('invoices.msj_date_expire_pay'));
                    }
                },
            ],
        ];
    }
}
