<?php

namespace App\Domain\Fields\Actions;

use App\Domain\Fields\Models\Field;
use App\Support\Actions\Action;
use Illuminate\Pagination\LengthAwarePaginator;

class ListFields implements Action
{
    public static function execute(array $params = [], $model = null): LengthAwarePaginator
    {
        return Field::select(
            'id',
            'name',
            'label',
            'type',
            'attributes',
        )->when($params['filter'], function ($query, $filter) {
            return $query->where(function ($query) use ($filter) {
                $query->where('name', 'like', '%'.$filter.'%')
                    ->orWhere('label', 'like', '%'.$filter.'%')
                    ->orWhere('type', 'like', '%'.$filter.'%')
                    ->orWhere('attributes', 'like', '%'.$filter.'%');
            });
        })->latest('id')->paginate(10);
    }

}
