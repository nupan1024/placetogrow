<?php

namespace App\Http\Requests\Admin\Field;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFieldRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'type' => ['required'],
            'label' => ['required'],
            'attributes' => ['array'],
        ];
    }

}
