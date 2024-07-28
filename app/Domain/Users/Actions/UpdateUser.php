<?php

namespace App\Domain\Users\Actions;

use App\Support\Actions\Action;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class UpdateUser implements Action
{
    public static function execute(array $params): bool
    {
        try {
            DB::table('model_has_roles')
                ->where('model_id', $params['user']->id)
                ->delete();

            $params['user']->name = $params['fields']['name'];
            $params['user']->email = $params['fields']['email'];
            $params['user']->role_id = $params['fields']['role_id'];
            $params['user']->status = $params['fields']['status'];
            $params['user']->assignRole(Role::findById($params['fields']['role_id'])->name);

            return $params['user']->save();
        } catch (\Exception $e) {
            Log::channel('Users')
                ->error('Error updating user: '.$e->getMessage());

            return false;
        }
    }

}
