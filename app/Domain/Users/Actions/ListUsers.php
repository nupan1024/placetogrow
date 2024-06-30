<?php

namespace App\Domain\Users\Actions;

use App\Domain\Users\Models\User;
use App\Support\Actions\Action;
use Illuminate\Pagination\LengthAwarePaginator;

class ListUsers implements Action
{
    public static function execute(array $params): LengthAwarePaginator
    {
        return User::select(
            'users.id',
            'users.name',
            'users.email',
            'roles.name as role',
        )->join('roles', 'users.role_id', '=', 'roles.id')
            ->when($params['filter'], function ($query, $filter) {
                return $query->where(function ($query) use ($filter) {
                    $query->where('users.name', 'like', '%'.$filter.'%')
                        ->orWhere('roles.name', 'like', '%'.$filter.'%')
                        ->orWhere('users.email', 'like', '%'.$filter.'%');
                });
            })->latest('users.id')->paginate(10);
    }
}
