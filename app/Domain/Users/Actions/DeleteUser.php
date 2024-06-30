<?php

namespace App\Domain\Users\Actions;

use App\Domain\Users\Models\User;
use App\Support\Actions\Action;

class DeleteUser implements Action
{
    public static function execute(array $params): bool
    {
        try {
            $user = User::find($params['id']);

            if (! $user) {
                return false;
            }

            return $user->delete();
        } catch (\Exception $e) {
            logger()->error($e->getMessage());

            return false;
        }
    }
}
