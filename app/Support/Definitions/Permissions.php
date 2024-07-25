<?php

namespace App\Support\Definitions;

enum Permissions: string
{
    case MICROSITES = 'List microsites';
    case CREATE_MICROSITE = 'Create microsites';
    case UPDATE_MICROSITE = 'Update microsites';
    case DELETE_MICROSITE = 'Delete microsites';
    case USERS = 'List users';
    case CREATE_USER = 'Create Users';
    case UPDATE_USER = 'Update users';
    case DELETE_USER = 'Delete users';
    case ROLES = 'List roles';
    case CREATE_ROLE = 'Create roles';
    case UPDATE_ROLE = 'Update roles';
    case DELETE_ROLE = 'Delete roles';

    public static function toArray(): array
    {
        $permissions = self::cases();
        $array = [];

        foreach ($permissions as $permission) {
            $array[] = [
                'name' => $permission->value,
                'guard_name' => 'web',
            ];
        }

        return $array;
    }

    public static function getNames(): array
    {
        $permissions = self::cases();
        $array = [];

        foreach ($permissions as $permission) {
            $array[$permission->name] = [
                'name' => $permission->value,
            ];
        }

        return $array;
    }

}
