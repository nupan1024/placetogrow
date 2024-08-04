<?php

namespace App\Domain\Microsites\Actions;

use App\Domain\Microsites\Models\Microsite;
use App\Support\Actions\Action;
use Illuminate\Database\Eloquent\Collection;

class GetMicrositesByStatus implements Action
{
    public static function execute(array $params): Collection
    {
        return Microsite::select('id', 'name')->where('status', $params['status'])->get();
    }
}
