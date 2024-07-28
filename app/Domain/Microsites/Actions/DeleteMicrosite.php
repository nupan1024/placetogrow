<?php

namespace App\Domain\Microsites\Actions;

use App\Support\Actions\Action;
use Illuminate\Support\Facades\Log;

class DeleteMicrosite implements Action
{
    public static function execute(array $params): bool
    {
        try {
            return $params['microsite']->delete();
        } catch (\Exception $e) {
            Log::channel('MicrositesAdmin')
                ->error('Error deleting microsite: ' . $e->getMessage());
            return false;
        }
    }

}
