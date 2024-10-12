<?php

namespace App\Domain\Imports\Actions;

use App\Domain\Imports\Models\Import;
use App\Support\Actions\Action;
use Illuminate\Pagination\LengthAwarePaginator;

class ListImport implements Action
{
    public static function execute(array $params = [], $model = null): LengthAwarePaginator
    {
        return Import::select(
            'imports.id',
            'imports.file_name',
            'imports.errors',
            'imports.status',
            'users.name as user',
            'imports.created_at',
        )
            ->join('users', 'imports.user_id', '=', 'users.id')
            ->when($params['filter'], function ($query, $filter) {
                return $query->where(function ($query) use ($filter) {
                    $query->where('imports.file_name', 'like', '%'.$filter.'%')
                        ->orWhere('imports.status', 'like', '%'.$filter.'%')
                        ->orWhere('imports.created_at', 'like', '%'.$filter.'%')
                        ->orWhere('users.name', 'like', '%'.$filter.'%');
                });
            })->latest('id')->paginate(10);
    }

}
