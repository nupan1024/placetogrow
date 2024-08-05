<?php

namespace App\Http\Requests\Admin\Field;

use Illuminate\Foundation\Http\FormRequest;

class CreateFieldRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'min:3',
                'regex:/^[a-z]+(_[a-z]+)*$/',
                'unique:fields'],
            'type' => ['required', 'min:3', 'max:50'],
            'label' => ['required', 'min:3', 'max:50'],
            'attributes' => ['array'],
        ];
    }
}
