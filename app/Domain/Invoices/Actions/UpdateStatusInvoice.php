<?php

namespace App\Domain\Invoices\Actions;

use App\Support\Actions\Action;
use Illuminate\Support\Facades\Log;

class UpdateStatusInvoice implements Action
{
    public static function execute(array $params): bool
    {
        try {

            $params['model']->status = $params['status'];
            return $params['model']->save();
        } catch (\Exception $e) {
            Log::channel('Invoices')->error('Error updating invoice: '.$e->getMessage());

            return false;
        }
    }
}
