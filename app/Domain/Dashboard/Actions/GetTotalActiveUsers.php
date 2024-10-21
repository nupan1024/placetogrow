<?php

namespace App\Domain\Dashboard\Actions;

use App\Domain\Users\Models\User;
use App\Support\Actions\Action;
use App\Support\Definitions\Roles;
use App\Support\Definitions\Status;
use Illuminate\Support\Facades\Cache;

class GetTotalActiveUsers implements Action
{
    public static function execute(array $params = [], $model = null): int
    {
        $cacheKey = 'dashboard_active_users';
        return Cache::remember($cacheKey, now()->addMinutes(60), function () {
            return User::where('status', Status::ACTIVE->value)
                ->where('role_id', Roles::GUEST->value)
                ->count();
        });
    }
}
