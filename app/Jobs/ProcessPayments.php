<?php

namespace App\Jobs;

use App\Domain\Payments\Actions\UpdatePaymentWithPaymentTypes;
use App\Domain\Payments\Models\Payment;
use App\Support\Definitions\PaymentStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessPayments implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function handle(): void
    {
        Payment::where('status', PaymentStatus::PENDING->value)
        ->chunk(100, function (Collection $payments) {
            foreach ($payments as $payment) {
                if ($payment->request_id && $payment->request_id != 0) {
                    UpdatePaymentWithPaymentTypes::execute([], $payment);
                } else {
                    UpdatePaymentWithPaymentTypes::execute([
                        'status' => PaymentStatus::REJECTED->value
                    ], $payment);
                }

            }
        });
    }

}
