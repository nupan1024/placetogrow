<?php

namespace App\Jobs;

use App\Domain\Invoices\Actions\UpdateExpiredInvoice;
use App\Domain\Invoices\Models\Invoice;
use App\Support\Definitions\StatusInvoices;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateInvoicesToExpired implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function handle(): void
    {
        Invoice::where('date_expire_pay', '<', now())
            ->where('status', '=', StatusInvoices::PENDING->name)
            ->chunk(100, function (Collection $invoices) {
                foreach ($invoices as $invoice) {
                    $value = $invoice->value + ($invoice->value * 0.05);

                    UpdateExpiredInvoice::execute([
                        'status' => StatusInvoices::EXPIRED->name,
                        'value' => $value,
                    ], $invoice);
                }
            });
    }

}
