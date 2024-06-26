<?php

namespace App\Domain\Users\Actions;

use App\Domain\Users\Models\User;
use App\Support\Actions\Action;

class UpdateUser implements Action
{
    public static function execute(array $params): bool
    {
        try {
            $user = User::find($params['id']);
            $user->name = $params['fields']['name'];
            $user->email = $params['fields']['email'];
            $user->role_id = $params['fields']['role_id'];
            $user->status = $params['fields']['status'];

            return $user->save();
        } catch (\Exception $e) {
            logger()->error($e->getMessage());

            return false;
        }
    }
}
