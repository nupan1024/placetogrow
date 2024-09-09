<?php

namespace App\Http\Requests\Admin\Field;

use Illuminate\Foundation\Http\FormRequest;

class CreateFieldRequest extends FormRequest
{
    public function rules(): array
    {
        $min = 'min:3';
        return [
            'name' => [
                'required',
                $min,
                'regex:/^[a-z]+(_[a-z]+)*$/',
                'unique:fields'],
            'type' => ['required', $min, 'max:50'],
            'label' => ['required', $min, 'max:50'],
            'attributes' => ['array'],
        ];
    }
}
