<?php

namespace App\Domain\Invoices\Actions;

use App\Support\Actions\Action;
use Illuminate\Support\Facades\Log;

class UpdateInvoice implements Action
{
    public static function execute(array $params): bool
    {
        try {
            $params['model']->microsite_id = $params['data']['microsite_id'];
            $params['model']->user_id = $params['data']['user_id'];
            $params['model']->value = $params['data']['value'];
            $params['model']->description = $params['data']['description'];
            return $params['model']->save();
        } catch (\Exception $e) {
            Log::channel('Invoices')->error('Error deleting invoice: '.$e->getMessage());

            return false;
        }
    }
}
