<?php

namespace App\Domain\Microsites\Actions;

use App\Support\Actions\Action;
use Illuminate\Support\Facades\Log;

class DeleteMicrosite implements Action
{
    public static function execute(array $params = [], $model = null): array
    {
        try {
            return ['status' => $model->delete()];
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
