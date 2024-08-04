<?php

namespace App\Domain\Invoices\Actions;

use App\Support\Actions\Action;
use App\Support\Definitions\StatusInvoices;
use Illuminate\Support\Facades\Log;

class DeleteInvoice implements Action
{
    public static function execute(array $params): bool
    {
        try {
            if ($params['model']->status != StatusInvoices::PENDING->name) {
                Log::channel('Invoices')
                    ->error(
                        'Error deleting invoice: '.__('invoices.error_delete')
                    );
                return false;
            }

            return $params['model']->delete();
        } catch (\Exception $e) {
            Log::channel('Invoices')
                ->error('Error updating invoice: '.$e->getMessage());

            return false;
        }
    }

}
