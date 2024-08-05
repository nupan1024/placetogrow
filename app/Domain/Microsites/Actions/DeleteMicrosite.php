<?php

namespace App\Domain\Microsites\Actions;

use App\Support\Actions\Action;
use Illuminate\Support\Facades\Log;

class DeleteMicrosite implements Action
{
    public static function execute(array $params): array
    {
        try {
            return ['status' => $params['microsite']->delete()];
        } catch (\Exception $e) {
            Log::channel('MicrositesAdmin')
                ->error('Error deleting microsite: ' . $e->getMessage());

            if($e->getCode() == '23000') {
                return [
                    'status' => false,
                    'code' => $e->getCode(),
                    'message' => __('microsites.invoices_error')
                ];
            }

            return [
                'status' => false,
                'code' => $e->getCode(),
                'message' => __('microsites.error_status_delete')
            ];
        }
    }

}
