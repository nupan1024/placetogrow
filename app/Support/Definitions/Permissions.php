<?php

namespace App\Support\Definitions;

enum Permissions: int
{
    case MICROSITES = 1;
    case CREATE_MICROSITE = 2;
    case UPDATE_MICROSITE = 3;
    case DELETE_MICROSITE = 4;
    case USERS = 5;
    case CREATE_USER = 7;
    case UPDATE_USER = 8;
    case DELETE_USER = 9;
    case ROLES = 10;
    case CREATE_ROLE = 11;
    case UPDATE_ROLE = 12;
    case DELETE_ROLE = 13;

    public static function toArray(): array
    {
        $cases = self::cases();
        $array = [];

        foreach ($cases as $case) {
            $array[] = [
                'id' => $case->value,
                'name' => ucwords(strtolower(str_replace('_', ' ', $case->name))),
                'guard_name' => 'web',
            ];
        }

        return $array;
    }
}
