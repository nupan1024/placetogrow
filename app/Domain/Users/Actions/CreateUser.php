<?php

namespace App\Domain\Users\Actions;

use App\Domain\Users\Models\User;
use App\Support\Actions\Action;
use Illuminate\Support\Facades\Hash;

class CreateUser implements Action
{
    public static function execute(array $params): bool
    {
        try {
            $user = new User();
            $user->name = $params['name'];
            $user->email = $params['email'];
            $user->role_id = $params['role_id'];
            $user->status = $params['status'];
            $user->password = Hash::make($params['password']);

            return $user->save();
        } catch (\Exception $e) {
            logger()->error($e->getMessage());

            return false;
        }
    }
}
