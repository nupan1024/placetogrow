<?php

namespace App\Http\Requests\Admin\Invoice;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
                Rule::when(
                    fn () => $this->canChangeDate(),
                    ['after_or_equal:today'],
                    ['same:date_expire_pay']
                ),
            ],
        ];
    }

    public function canChangeDate(): bool
    {
        $createdAt = Carbon::parse($this->invoice->created_at);
        $threeDaysAgo = Carbon::now()->subDays(3);
        return $createdAt >= $threeDaysAgo;
    }
}
