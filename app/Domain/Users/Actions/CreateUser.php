<?php

namespace App\Domain\Users\Actions;

use App\Domain\Users\Models\User;
use App\Support\Actions\Action;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

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
            $user->email_verified_at = now();

            return $user->save();
        } catch (\Exception $e) {
            Log::channel('Users')->error('Error creating user: '.$e->getMessage());

            return false;
        }
    }
}
