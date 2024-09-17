<?php

namespace App\Domain\Microsites\Actions;

use App\Domain\Microsites\Models\Microsite;
use App\Support\Actions\Action;
use App\Support\Definitions\Status;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class ListMicrositesForGuest implements Action
{
    public static function execute(array $params = [], $model = null): LengthAwarePaginator
    {
        $cacheKey = 'microsites_page_'.$params['page'].'_filter_'.$params['filter'];

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($params) {
            return Microsite::select(
                'microsites.id',
                'microsites.name',
                'categories.name as category',
                'microsites.logo_path',
                'microsites.description',
                'microsites_types.name as type',
            )
                ->where('microsites.status', Status::ACTIVE->value)
                ->join('categories', 'microsites.category_id', '=', 'categories.id')
                ->join('microsites_types', 'microsites.microsites_type_id', '=', 'microsites_types.id')
                ->when($params['filter'], function ($query, $filter) {
                    return $query->where(function ($query) use ($filter) {
                        $query->where('microsites.name', 'like', '%'.$filter.'%')
                            ->orWhere('categories.name', 'like', '%'.$filter.'%')
                            ->orWhere('microsites.description', 'like', '%'.$filter.'%')
                            ->orWhere('microsites_types.name', 'like', '%'.$filter.'%');
                    });
                })->latest('microsites.id')->paginate(4);
        });
    }
}
