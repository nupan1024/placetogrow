<?php

namespace App\Domain\Roles\Actions;

use App\Support\Actions\Action;
use Illuminate\Support\Facades\Log;

class UpdateRole implements Action
{
    public static function execute(array $params): bool
    {
        try {
            $params['role']->name = $params['fields']['name'];
            $params['role']->syncPermissions($params['fields']['permissions']);
            return $params['role']->save();
        } catch (\Exception $e) {
            Log::channel('Role')->error('Error updating role: '.$e->getMessage());

            return false;
        }
    }
}
