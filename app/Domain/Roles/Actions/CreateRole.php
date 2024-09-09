<?php

namespace App\Domain\Roles\Actions;

use App\Support\Actions\Action;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class CreateRole implements Action
{
    public static function execute(array $params = [], $model = null): bool
    {
        try {
            $role = new Role();
            $role->name = $params['name'];
            $role->syncPermissions($params['permissions']);
            $response = $role->save();

            if ($response) {
                Cache::forget(config('cache.stores.key.roles'));
            }

            return $response;
        } catch (\Exception $e) {
            Log::channel('Roles')->error('Error creating role: '.$e->getMessage());

            return false;
        }
    }
}
