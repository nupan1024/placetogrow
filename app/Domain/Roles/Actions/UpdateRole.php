<?php

namespace App\Domain\Roles\Actions;

use App\Support\Actions\Action;
use Illuminate\Support\Facades\Log;

class UpdateRole implements Action
{
    public static function execute(array $params = [], $model = null): bool
    {
        try {
            $model->name = $params['name'];
            $model->syncPermissions($params['permissions']);
            return $model->save();
        } catch (\Exception $e) {
            Log::channel('Roles')->error('Error updating role: '.$e->getMessage());

            return false;
        }
    }
}
