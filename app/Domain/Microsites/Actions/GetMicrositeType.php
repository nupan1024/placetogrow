<?php

namespace App\Domain\Microsites\Actions;

use App\Domain\Microsites\Models\Microsite;
use App\Support\Actions\Action;
use Illuminate\Database\Eloquent\Model;

class GetMicrositeType implements Action
{
    public static function execute(array $params): ?Model
    {
        return Microsite::select('microsites_type_id')
            ->where('id', $params['id'])->first();
    }
}
