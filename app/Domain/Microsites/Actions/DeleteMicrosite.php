<?php

namespace App\Domain\Microsites\Actions;

use App\Domain\Microsites\Models\Microsite;
use App\Support\Actions\Action;
use Illuminate\Support\Facades\Log;

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
            Log::channel('MicrositesAdmin')->error('Error deleting microsite: '.$e->getMessage());

            return false;
        }
    }
}
