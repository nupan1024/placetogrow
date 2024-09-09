<?php

namespace App\Jobs;

use App\Domain\Payments\Actions\UpdatePayment;
use App\Domain\Payments\Models\Payment;
use App\Support\Definitions\PaymentStatus;
use App\Support\Services\Payments\Gateways\PlaceToPayService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateStatusPayments implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function handle(): void
    {
        /**
         * @var PlaceToPayService $placetopay
         */
        $placetopay = app(PlaceToPayService::class);
        Payment::where('status', PaymentStatus::PENDING->value)
        ->chunk(100, function (Collection $payments) use ($placetopay) {
            foreach ($payments as $payment) {
                if($payment->request_id && $payment->request_id != 0) {
                    $statusPayment = $placetopay->init()->query($payment->request_id);

                    UpdatePayment::execute([
                        'status' => $statusPayment->status()->status()
                    ], $payment);
                } else {
                    UpdatePayment::execute([
                        'status' => PaymentStatus::REJECTED->value
                    ], $payment);
                }

            }
        });
    }

}
