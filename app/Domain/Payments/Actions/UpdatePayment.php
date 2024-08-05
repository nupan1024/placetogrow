<?php

namespace App\Domain\Payments\Actions;

use App\Domain\Invoices\Models\Invoice;
use App\Support\Actions\Action;

class UpdatePayment implements Action
{
    public static function execute(array $params): bool
    {
        if (is_numeric($params['payment']->invoice_id)) {
            $invoice = Invoice::find($params['payment']->invoice_id);
            $invoice->status = $params['status'];
            $invoice->save();
        }

        $params['payment']->process_url = $params['url'] ?? $params['payment']->process_url;
        $params['payment']->request_id = $params['request_id'] ?? $params['payment']->request_id;
        $params['payment']->status = $params['status'] ?? $params['payment']->status;
        return $params['payment']->save();
    }

}
