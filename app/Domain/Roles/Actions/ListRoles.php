<?php

namespace App\Domain\Roles\Actions;

use App\Support\Actions\Action;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Role;

class ListRoles implements Action
{
    public static function execute(array $params = [], $model = null): LengthAwarePaginator
    {
        $cacheKey = 'roles_page_'.$params['page'].'_filter_'.$params['filter'];
        return Cache::remember(
            $cacheKey,
            now()->addMinutes(60),
            function () use ($params) {
                return Role::select(
                    'roles.id',
                    'roles.name'
                )->when($params['filter'], function ($query, $filter) {
                    return $query->where(function ($query) use ($filter) {
                        $query->where('roles.name', 'like', '%'.$filter.'%');
                    });
                })->latest('roles.id')->paginate(10);
            }
        );
    }

}
