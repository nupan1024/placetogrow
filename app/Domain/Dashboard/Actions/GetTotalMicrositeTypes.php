<?php

namespace App\Domain\Dashboard\Actions;

use App\Domain\Microsites\Models\Microsite;
use App\Support\Actions\Action;
use App\Support\Definitions\Status;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class GetTotalMicrositeTypes implements Action
{
    public static function execute(array $params = [], $model = null): array
    {
        $cacheKey = 'dashboard_total_microsite_types';
        return Cache::remember($cacheKey, now()->addMinutes(60), function () {
            return Microsite::join('microsites_types', 'microsites.microsites_type_id', '=', 'microsites_types.id')
                ->select(
                    'microsites.microsites_type_id',
                    'microsites_types.name as type_name',
                    DB::raw('COUNT(microsites.id) as total')
                )
                ->where('microsites.status', Status::ACTIVE->value)
                ->groupBy('microsites.microsites_type_id', 'microsites_types.name')
                ->get()->toArray();
        });
    }
}
