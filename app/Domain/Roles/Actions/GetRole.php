<?php

namespace App\Domain\Roles\Actions;

use App\Support\Actions\Action;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Contracts\Role as ContractsRole;

class GetRole implements Action
{
    public static function execute(array $params = [], $model = null): ContractsRole|Role
    {
        return  Role::findByName(ucwords(strtolower(str_replace('_', ' ', $params['name']))));
    }

}
