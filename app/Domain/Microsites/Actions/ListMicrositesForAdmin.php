<?php

namespace App\Domain\Microsites\Actions;

use App\Domain\Microsites\Models\Microsite;
use App\Support\Actions\Action;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class ListMicrositesForAdmin implements Action
{
    public static function execute(array $params): LengthAwarePaginator
    {
        $cacheKey = 'admin_microsites_page_'.$params['page'].'_filter_'.$params['filter'];

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($params) {
            return Microsite::select(
                'id',
                'name',
                'category_id',
                'microsites_type_id',
                'status')
                ->with(['category:id,name', 'type:id,name'])
                ->when($params['filter'], function ($query, $filter) {
                    return $query->where(function ($query) use ($filter) {
                        $query->where('name', 'like', '%'.$filter.'%')
                            ->orWhereHas('category', function ($query) use ($filter) {
                                $query->where('name', 'like', '%'.$filter.'%');
                            })
                            ->orWhereHas('type', function ($query) use ($filter) {
                                $query->where('name', 'like', '%'.$filter.'%');
                            });
                    });
                })->latest('id')->paginate(10);
        });

    }
}
