<?php

namespace App\Domain\Invoices\Actions;

use App\Domain\Invoices\Models\Invoice;
use App\Support\Actions\Action;
use App\Support\Definitions\StatusInvoices;

class GetInvoicesByMicrositeAndUser implements Action
{
    public static function execute(array $params = [], $model = null): array
    {
        return Invoice::where('microsite_id', $params['microsite_id'])
             ->where('user_id', $params['user_id'])
             ->where('status', StatusInvoices::PENDING->name)->get()->toArray();
    }

}
