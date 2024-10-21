<?php

namespace App\Domain\Invoices\Actions;

use App\Support\Actions\Action;
use Illuminate\Support\Facades\Log;

class UpdateExpiredInvoice implements Action
{
    public static function execute(array $params = [], $model = null): bool
    {
        try {

            $model->status = $params['status'];
            $model->value = $params['value'];
            return $model->save();
        } catch (\Exception $e) {
            Log::channel('Invoices')->error('Error updating invoice: '.$e->getMessage());

            return false;
        }
    }
}
