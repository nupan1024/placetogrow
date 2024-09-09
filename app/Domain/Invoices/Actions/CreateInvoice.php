<?php

namespace App\Domain\Invoices\Actions;

use App\Domain\Invoices\Models\Invoice;
use App\Support\Actions\Action;
use App\Support\Definitions\StatusInvoices;
use Illuminate\Support\Facades\Log;

class CreateInvoice implements Action
{
    public static function execute(array $params = [], $model = null): bool
    {
        try {
            $invoice = new Invoice();
            $invoice->microsite_id = $params['microsite_id'];
            $invoice->user_id = $params['user_id'];
            $invoice->value = $params['value'];
            $invoice->description = $params['description'];
            $invoice->status = StatusInvoices::PENDING->name;
            $invoice->code = 'MICROSITE_PLACETOGROW_'.time();

            return $invoice->save();
        } catch (\Exception $e) {
            Log::channel('Invoices')->error('Error creating invoice: '.$e->getMessage());

            return false;
        }
    }
}
