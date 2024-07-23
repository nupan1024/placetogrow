<?php

namespace App\Domain\Users\Actions;

use App\Support\Actions\Action;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class UpdateUser implements Action
{
    public static function execute(array $params): bool
    {
        try {
            $params['user']->name = $params['fields']['name'];
            $params['user']->email = $params['fields']['email'];
            $params['user']->role_id = $params['fields']['role_id'];
            $params['user']->assignRole(Role::findById($params['fields']['role_id'])->name);
            $params['user']->status = $params['fields']['status'];

            return $params['user']->save();
        } catch (\Exception $e) {
            Log::channel('Users')->error('Error updating microsite: '.$e->getMessage());

            return false;
        }
    }
}
