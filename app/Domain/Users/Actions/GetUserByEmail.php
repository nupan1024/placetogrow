<?php

namespace App\Domain\Users\Actions;

use App\Domain\Users\Models\User;
use App\Support\Actions\Action;

class GetUserByEmail implements Action
{
    public static function execute(array $params = [], $model = null): User|false
    {
        $user = User::select('id')->where('email', $params['email'])->first();
        return $user ?? false;
    }
}
