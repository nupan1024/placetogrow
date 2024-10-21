<?php

namespace App\Jobs;

use App\Domain\Invoices\Actions\UpdateExpiredInvoice;
use App\Domain\Invoices\Models\Invoice;
use App\Domain\Settings\Models\Setting;
use App\Support\Definitions\StatusInvoices;
use App\Support\Services\Mail\Invoice\InvoiceCloseExpireEmail;
use App\Support\Services\Mail\Invoice\InvoiceExpiredEmail;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessInvoices implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function handle(): void
    {
        Invoice::where('date_expire_pay', Carbon::tomorrow()->toDateString())
            ->where('status', StatusInvoices::PENDING->name)
            ->chunk(100, function (Collection $invoices) {
                foreach ($invoices as $invoice) {
                    ProcessSendEmail::dispatch(
                        InvoiceCloseExpireEmail::class,
                        $invoice->user,
                        ['invoice' => $invoice]
                    );
                }
            });

        Invoice::where('date_expire_pay', '<', now())
            ->where('status', '=', StatusInvoices::PENDING->name)
            ->chunk(100, function (Collection $invoices) {
                foreach ($invoices as $invoice) {
                    $penalty = Setting::where('key', 'invoice_penalty')->first();
                    $penalty = $penalty->value ?? config('invoice.invoice_penalty');
                    $value = $invoice->value + ($invoice->value * ($penalty / 100));

                    UpdateExpiredInvoice::execute([
                        'status' => StatusInvoices::EXPIRED->name,
                        'value' => $value,
                    ], $invoice);

                    ProcessSendEmail::dispatch(
                        InvoiceExpiredEmail::class,
                        $invoice->user,
                        ['invoice' => $invoice]
                    );
                }
            });
    }

}
