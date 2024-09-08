<?php

namespace App\Http\Requests\Admin\Invoice;

use Illuminate\Foundation\Http\FormRequest;

class ImportInvoicesRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'microsite_id' => ['required', 'exists:microsites,id'],
            'file' => [
                'required', 'file', 'mimes:csv,xlsx', 'max:2048',
            ],
        ];
    }
}
