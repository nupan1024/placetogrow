<?php

namespace App\Domain\Invoices\Actions;

use App\Domain\Invoices\Models\Invoice;
use App\Support\Actions\Action;

class GetInvoiceByCode implements Action
{
    public static function execute(array $params = [], $model = null): ?Invoice
    {
        return Invoice::where('code', $params['code'])->first();
    }

}
