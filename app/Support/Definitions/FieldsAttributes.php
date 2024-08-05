<?php

namespace App\Support\Definitions;

enum FieldsAttributes: string
{
    case REQUIRED = 'required';
    case AUTOCOMPLETE = 'autocomplete';
    case AUTOFOCUS = 'autofocus';
    public static function toArray(): array
    {
        $attributes = self::cases();
        $array = [];

        foreach ($attributes as $attribute) {
            $array[] = [
                'id' => $attribute->value,
                'name' => ucwords(strtolower(str_replace('_', ' ', $attribute->name)))
            ];
        }

        return $array;
    }
}
