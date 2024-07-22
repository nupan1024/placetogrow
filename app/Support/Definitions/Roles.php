<?php

namespace App\Support\Definitions;

enum Roles: int
{
    case SUPER_ADMIN = 1;
    case GUEST = 2;

    public static function toArray(): array
    {
        $roles = self::cases();
        $array = [];

        foreach ($roles as $role) {
            $array[] = [
                'id' => $role->value,
                'name' => ucwords(strtolower(str_replace('_', ' ', $role->name))),
                'guard_name' => 'web',
            ];
        }

        return $array;
    }
}
