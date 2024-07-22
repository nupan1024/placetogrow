<?php

namespace App\Domain\Roles\Actions;

use App\Support\Actions\Action;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class CreateRole implements Action
{
    public static function execute(array $params): bool
    {
        try {
            $role = new Role();
            $role->name = $params['name'];
            $role->syncPermissions($params['permissions']);
            return $role->save();
        } catch (\Exception $e) {
            Log::channel('Role')->error('Error creating role: '.$e->getMessage());

            return false;
        }
    }
}
