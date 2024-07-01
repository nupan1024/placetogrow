<?php

namespace App\Domain\Microsites\Actions;

use App\Domain\Microsites\Models\Microsite;
use App\Support\Actions\Action;
use App\Support\Definitions\Status;
use Illuminate\Pagination\LengthAwarePaginator;

class ListMicrositesForAdmin implements Action
{
    public static function execute(array $params): LengthAwarePaginator
    {
        return Microsite::select(
            'microsites.id',
            'microsites.name',
            'categories.name as category',
            'microsites_types.name as type',
            'microsites.status',
        )
            ->where('microsites.status', Status::ACTIVE->value)
            ->join('categories', 'microsites.category_id', '=', 'categories.id')
            ->join('microsites_types', 'microsites.microsites_type_id', '=', 'microsites_types.id')
            ->when($params['filter'], function ($query, $filter) {
                return $query->where(function ($query) use ($filter) {
                    $query->where('microsites.name', 'like', '%'.$filter.'%')
                        ->orWhere('categories.name', 'like', '%'.$filter.'%')
                        ->orWhere('microsites_types.name', 'like', '%'.$filter.'%');
                });
            })->latest('microsites.id')->paginate(10);
    }
}
