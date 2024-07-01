<?php

namespace App\Support\Definitions;

enum Roles: int
{
    case ADMIN = 1;
    case GUEST = 2;

    public static function toArray(): array
    {
        $cases = self::cases();
        $array = [];

        foreach ($cases as $case) {
            $array[] = [
                'id' => $case->value,
                'name' => ucfirst(strtolower($case->name)),
                'guard_name' => 'web',
            ];
        }

        return $array;
    }
}
