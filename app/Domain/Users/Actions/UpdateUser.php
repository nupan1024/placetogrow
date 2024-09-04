<?php

namespace App\Domain\Users\Actions;

use App\Support\Actions\Action;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class UpdateUser implements Action
{
    public static function execute(array $params = [], $model = null): bool
    {
        try {
            DB::table('model_has_roles')
                ->where('model_id', $model->id)
                ->delete();

            $model->name = $params['name'];
            $model->email = $params['email'];
            $model->role_id = $params['role_id'];
            $model->status = $params['status'];
            $model->assignRole(Role::findById($params['role_id'])->name);
            $response = $model->save();

            if ($response) {
                Cache::forget(config('cache.stores.key.users'));
            }

            return $response;
        } catch (\Exception $e) {
            Log::channel('Users')
                ->error('Error updating user: '.$e->getMessage());

            return false;
        }
    }

}
