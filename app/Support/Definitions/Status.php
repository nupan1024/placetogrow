<?php

namespace App\Support\Definitions;

enum Status: int
{
    case INACTIVE = 0;
    case ACTIVE = 1;

    public static function asOptions(): array
    {
        $cases = self::cases();
        $array = [];

        foreach ($cases as $case) {
            $array[] = ['name' => ucfirst(strtolower($case->name)), 'id' => $case->value];
        }

        return $array;
    }
}
