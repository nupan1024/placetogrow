<?php

namespace App\Domain\Roles\Actions;

use App\Support\Actions\Action;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class UpdateRole implements Action
{
    public static function execute(array $params = [], $model = null): bool
    {
        try {
            $model->name = $params['name'];
            $model->syncPermissions($params['permissions']);
            $response = $model->save();

            if ($response) {
                Cache::forget(config('cache.stores.key.roles'));
            }

            return $response;
        } catch (\Exception $e) {
            Log::channel('Roles')->error('Error updating role: '.$e->getMessage());

            return false;
        }
    }
}
