<?php

namespace App\Domain\Invoices\Actions;

use App\Support\Actions\Action;
use Illuminate\Support\Facades\Log;

class UpdateStatusInvoice implements Action
{
    public static function execute(array $params = [], $model = null): bool
    {
        try {

            $model->status = $params['status'];
            return $model->save();
        } catch (\Exception $e) {
            Log::channel('Invoices')->error('Error updating invoice: '.$e->getMessage());

            return false;
        }
    }
}
