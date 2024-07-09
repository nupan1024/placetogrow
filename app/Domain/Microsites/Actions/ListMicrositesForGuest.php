<?php

namespace App\Domain\Microsites\Actions;

use App\Domain\Microsites\Models\Microsite;
use App\Support\Actions\Action;
use App\Support\Definitions\Status;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class ListMicrositesForGuest implements Action
{
    public static function execute(array $params): LengthAwarePaginator
    {
        $cacheKey = 'microsites_'.md5(json_encode($params));

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($params) {
            return Microsite::select(
                'microsites.id',
                'microsites.name',
                'category_id',
                'microsites.logo_path',
                'microsites.description',
            )
                ->where('microsites.status', Status::ACTIVE->value)
                ->with(['category:id,name'])
                ->when($params['filter'], function ($query, $filter) {
                    return $query->where(function ($query) use ($filter) {
                        $query->where('microsites.name', 'like', '%'.$filter.'%')
                            ->orWhereHas('category', function ($query) use ($filter) {
                                $query->where('name', 'like', '%'.$filter.'%');
                            })
                            ->orWhere('microsites.description', 'like', '%'.$filter.'%');
                    });
                })->latest('microsites.id')->paginate(4);
        });
    }
}
