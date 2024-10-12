<?php

namespace App\Support\Observers;

use App\Domain\Invoices\Models\Invoice;
use App\Jobs\ProcessSendEmail;
use App\Support\Services\Mail\Invoice\InvoiceCreatedEmail;
use App\Support\Services\Mail\Invoice\InvoiceDeletedEmail;
use App\Support\Services\Mail\Invoice\InvoiceUpdatedEmail;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

class InvoiceObserver implements ShouldHandleEventsAfterCommit
{
    public function created(Invoice $invoice): void
    {
        dispatch(new ProcessSendEmail(InvoiceCreatedEmail::class, collect()->push($invoice->user), ['invoice' => $invoice]));

    }

    public function updated(Invoice $invoice): void
    {
        $params = [
            'invoice' => $invoice,
            'data' => $invoice->getDirty(),
        ];

        dispatch(new ProcessSendEmail(InvoiceUpdatedEmail::class, collect()->push($invoice->user), $params));
    }

    public function deleted(Invoice $invoice): void
    {
        dispatch(new ProcessSendEmail(InvoiceDeletedEmail::class, collect()->push($invoice->user), $invoice->toArray()));

    }

}
