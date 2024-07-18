<?php

namespace App\Domain\Microsites\Actions;

use App\Domain\Microsites\Models\Microsite;
use App\Support\Actions\Action;
use Illuminate\Support\Facades\Log;

class DeleteMicrosite implements Action
{
    public static function execute(array $params): bool
    {
        $microsite = Microsite::find($params['id']);

        if (!$microsite) {
            Log::channel('MicrositesAdmin')
                ->error('Error deleting microsite: Not found the microsite');
            return false;
        }

        return $microsite->delete();
    }

}
