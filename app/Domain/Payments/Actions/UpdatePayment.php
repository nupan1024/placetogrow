<?php

namespace App\Domain\Payments\Actions;

use App\Domain\Invoices\Models\Invoice;
use App\Support\Actions\Action;

class UpdatePayment implements Action
{
    public static function execute(array $params = [], $model = null): bool
    {
        if (is_numeric($model->invoice_id)) {
            $invoice = Invoice::find($model->invoice_id);
            $invoice->status = $params['status'];
            $invoice->save();
        }

        $model->process_url = $params['url'] ?? $model->process_url;
        $model->request_id = $params['request_id'] ?? $model->request_id;
        $model->status = $params['status'] ?? $model->status;
        return $model->save();
    }

}
