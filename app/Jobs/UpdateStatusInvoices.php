<?php

namespace App\Jobs;

use App\Domain\Invoices\Actions\UpdateStatusInvoice;
use App\Domain\Invoices\Models\Invoice;
use App\Domain\Microsites\Models\Microsite;
use App\Support\Definitions\StatusInvoices;
use App\Support\Services\Payments\Gateways\PlaceToPayService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateStatusInvoices implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function handle(): void
    {
        Microsite::where('date_expire_pay', '<', now())
            ->chunk(100, function (Collection $microsites) {
                foreach ($microsites as $microsite) {
                    Invoice::where('microsite_id', $microsite->id)
                        ->where('status', '=', StatusInvoices::PENDING->name)
                        ->get()
                        ->each(function (Invoice $invoice) {
                            UpdateStatusInvoice::execute([
                                'model' => $invoice,
                                'status' => StatusInvoices::EXPIRED->name,
                            ]);
                        });
                }
            });
    }

}
