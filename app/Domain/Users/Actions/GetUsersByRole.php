<?php

namespace App\Domain\Users\Actions;

use App\Domain\Users\Models\User;
use App\Support\Actions\Action;
use Illuminate\Database\Eloquent\Collection;

class GetUsersByRole implements Action
{
    public static function execute(array $params): Collection
    {
        return User::select('id', 'name', 'email')->where('role_id', $params['role'])->get();
    }
}
