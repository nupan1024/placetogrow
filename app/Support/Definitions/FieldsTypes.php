<?php

namespace App\Support\Definitions;

enum FieldsTypes: string
{
    case TEXT_AREA = 'textarea';
    case TEXT_INPUT = 'input';
    case CHECK_BOX = 'checkbox';
    public static function toArray(): array
    {
        $fields = self::cases();
        $array = [];

        foreach ($fields as $field) {
            $array[] = [
                'id' => $field->value,
                'name' => ucwords(strtolower(str_replace('_', ' ', $field->name)))
            ];
        }

        return $array;
    }
}
