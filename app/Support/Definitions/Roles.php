<?php

namespace App\Support\Definitions;

enum Roles: int
{
    case ADMIN = 1;

    public static function toArray(): array
    {
        $cases = self::cases();
        $array = [];

        foreach ($cases as $c) {
            $array[] = [
                'id' => $c->value,
                'name' => strtolower($c->name),
                'guard_name' => 'web',
            ];
        }

        return $array;
    }
}
