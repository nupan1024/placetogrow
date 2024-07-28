<?php

namespace App\Domain\Roles\Actions;

use App\Domain\Users\Models\User;
use App\Support\Actions\Action;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DeleteRole implements Action
{
    public static function execute(array $params): bool
    {
        $user = User::where('role_id', $params['role']->id)->first();
        if ($user) {
            Log::channel('Roles')
                ->error('Error deleting role: Users has this role '.$params['role']->name);
            return false;
        }

        $permissions = $params['role']->permissions->pluck('id')->toArray();
        $deletePermissions = DB::table('role_has_permissions')
            ->whereIn('permission_id', $permissions)
            ->where('role_id', $params['role']->id);
        if (!$deletePermissions) {
            Log::channel('Roles')
                ->error('Error deleting role: Error removing permissions');
            return false;
        }

        return $params['role']->delete();
    }

}
