<?php

namespace App\Domain\Roles\Actions;

use App\Domain\Users\Models\User;
use App\Support\Actions\Action;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DeleteRole implements Action
{
    public static function execute(array $params = [], $model = null): bool
    {
        $user = User::where('role_id', $model->id)->first();
        if ($user) {
            Log::channel('Roles')
                ->error('Error deleting role: Users has this role '.$model->name);
            return false;
        }

        $permissions = $model->permissions->pluck('id')->toArray();
        $deletePermissions = DB::table('role_has_permissions')
            ->whereIn('permission_id', $permissions)
            ->where('role_id', $model->id);
        if (!$deletePermissions->exists()) {
            Log::channel('Roles')
                ->error('Error deleting role: Error removing permissions');
            return false;
        }

        $response = $model->delete();

        if ($response) {
            Cache::forget(config('cache.stores.key.roles'));
        }

        return $response;
    }

}
