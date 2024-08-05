<?php

namespace App\Domain\Invoices\Actions;

use App\Domain\Invoices\Models\Invoice;
use App\Support\Actions\Action;
use App\Support\Definitions\StatusInvoices;
use Illuminate\Support\Facades\Log;

class CreateInvoice implements Action
{
    public static function execute(array $params): bool
    {
        try {
            $field = new Invoice();
            $field->microsite_id = $params['microsite_id'];
            $field->user_id = $params['user_id'];
            $field->value = $params['value'];
            $field->description = $params['description'];
            $field->status = StatusInvoices::PENDING->name;
            $field->code = 'MICROSITE_PLACETOGROW_'.time();

            return $field->save();
        } catch (\Exception $e) {
            Log::channel('Invoices')->error('Error creating invoice: '.$e->getMessage());

            return false;
        }
    }
}
