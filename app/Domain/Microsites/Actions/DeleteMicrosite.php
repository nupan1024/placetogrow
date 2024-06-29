<?php

namespace App\Domain\Microsites\Actions;

use App\Domain\Microsites\Models\Microsite;
use App\Support\Actions\Action;

class DeleteMicrosite implements Action
{
    public static function execute(array $params): bool
    {
        try {
            $microsite = Microsite::find($params['id']);

            if (! $microsite) {
                return false;
            }

            return $microsite->delete();
        } catch (\Exception $e) {
            logger()->error($e->getMessage());

            return false;
        }
    }
}
