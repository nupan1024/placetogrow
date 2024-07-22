<?php

namespace App\Domain\Roles\Actions;

use App\Support\Actions\Action;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DeleteRole implements Action
{
    public static function execute(array $params): bool
    {
        $permissions = $params['role']->permissions->pluck('id')->toArray();
        $deletePermissions = DB::table('role_has_permissions')
        ->whereIn('permission_id', $permissions)
        ->where('role_id', $params['role']->id);
        if (!$deletePermissions) {
            Log::channel('Role')
                ->error('Error deleting role: Error removing permissions');
            return false;
        }

        return $params['role']->delete();
    }

}
