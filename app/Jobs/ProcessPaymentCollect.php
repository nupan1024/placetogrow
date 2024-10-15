<?php

namespace App\Jobs;

use App\Contracts\PaymentService;
use App\Domain\Payments\Actions\UpdatePayment;
use App\Domain\SubscriptionUser\Models\SubscriptionUser;
use App\Support\Definitions\PaymentGateway;
use App\Support\Definitions\StatusInvoices;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessPaymentCollect implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function handle(): void
    {
        SubscriptionUser::where('status', StatusInvoices::PENDING->name)
            ->chunk(100, function (Collection $subscriptionUsers) {
                foreach ($subscriptionUsers as $subscriptionUser) {
                    $payment = $subscriptionUser->payment;
                    /** @var PaymentService $paymentService */
                    $paymentService = app(PaymentService::class, [
                        'payment' => $payment,
                        'gateway' => PaymentGateway::PLACETOPAY->value,
                    ]);

                    $token = "";
                    $dataToken = $paymentService->getToken($payment);
                    $statusPayment = $subscriptionUser->status;
                    if ($dataToken['status']) {
                        $payer = [
                            'name' => $payment->name,
                            'email' => $payment->email,
                            'type_document' => $payment->type_document,
                            'num_document' => $payment->num_document,
                        ];

                        $response = $paymentService->createCollect($payer, $dataToken['token']);
                        $statusPayment = $response['status_payment'];
                        UpdatePayment::execute([
                            'status' => $response['status_payment'],
                        ], $subscriptionUser->payment);
                    }

                    $subscriptionUser->update([
                        'token' => $token,
                        'status' => $statusPayment,
                    ]);
                }
            });
    }
}
