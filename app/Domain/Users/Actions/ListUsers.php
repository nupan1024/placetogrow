<?php

namespace App\Domain\Users\Actions;

use App\Domain\Users\Models\User;
use App\Support\Actions\Action;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class ListUsers implements Action
{
    public static function execute(array $params): LengthAwarePaginator
    {
        $cacheKey = 'users_page_'.$params['page'].'_filter_'.$params['filter'];

        return Cache::remember($cacheKey, now()->addMinutes(60), function () use ($params) {
            return User::select(
                'users.id',
                'users.name',
                'users.email',
                'users.status',
                'role_id',
            )->with(['role:id,name'])
                ->when($params['filter'], function ($query, $filter) {
                    return $query->where(function ($query) use ($filter) {
                        $query->where('users.name', 'like', '%'.$filter.'%')
                            ->orWhereHas('role', function ($query) use ($filter) {
                                $query->where('name', 'like', '%'.$filter.'%');
                            })
                            ->orWhere('users.email', 'like', '%'.$filter.'%');
                    });
                })->latest('users.id')->paginate(10);
        });
    }
}
