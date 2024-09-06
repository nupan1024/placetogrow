<?php

namespace App\Domain\Invoices\Actions;

use App\Domain\Invoices\Models\Invoice;
use App\Imports\InvoicesImport;
use App\Support\Actions\Action;
use Illuminate\Pagination\LengthAwarePaginator;
use Maatwebsite\Excel\Facades\Excel;

class ImportInvoices implements Action
{
    public static function execute(array $params = [], $model = null): bool
    {
        Excel::import(app(InvoicesImport::class), $params['file']);
        return true;
    }

}
