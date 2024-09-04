<?php

namespace App\Domain\Microsites\Actions;

use App\Support\Actions\Action;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class DeleteMicrosite implements Action
{
    public static function execute(array $params = [], $model = null): array
    {
        try {
            $response = $model->delete();

            if ($response) {
                Cache::forget(config('cache.stores.key.microsites_admin'));
                Cache::forget(config('cache.stores.key.microsites'));
            }

            return ['status' => $response];
        } catch (\Exception $e) {
            Log::channel('MicrositesAdmin')
                ->error('Error deleting microsite: ' . $e->getMessage());

            return [
                'status' => false,
                'code' => $e->getCode(),
                'message' => __('microsites.invoices_error')
            ];

        }
    }

}
