<?php

namespace App\Domain\Users\Actions;

use App\Domain\Users\Models\User;
use App\Support\Actions\Action;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class CreateUser implements Action
{
    public static function execute(array $params = [], $model = null): bool
    {
        try {
            $user = new User();
            $user->name = $params['name'];
            $user->email = $params['email'];
            $user->role_id = $params['role_id'];
            $user->assignRole(Role::findById($params['role_id'])->name);
            $user->status = $params['status'];
            $user->password = Hash::make($params['password']);
            $user->email_verified_at = now();
            $response = $user->save();

            if ($response) {
                Cache::forget(config('cache.stores.key.users'));
            }

            return $response;
        } catch (\Exception $e) {
            Log::channel('Users')->error('Error creating user: '.$e->getMessage());

            return false;
        }
    }
}
