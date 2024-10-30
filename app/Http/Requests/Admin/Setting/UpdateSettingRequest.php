<?php

namespace App\Http\Requests\Admin\Setting;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'attempts' => ['required', 'numeric'],
            'period_time' => ['required', 'numeric'],
            'invoice_penalty' => ['required', 'numeric'],
        ];
    }
}
