<?php

namespace App\Jobs;

use App\Contracts\PaymentService;
use App\Domain\Payments\Models\Payment;
use App\Domain\Settings\Models\Setting;
use App\Domain\SubscriptionUser\Actions\UpdateSubscriptionUser;
use App\Support\Definitions\PaymentGateway;
use App\Support\Definitions\PaymentStatus;
use App\Support\Definitions\Status;
use App\Support\Services\Mail\Payment\PaymentStatusEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessRetryPaymentSubscription implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(private readonly Payment $payment)
    {
    }

    /**
     * @throws \Exception
     */
    public function handle(): void
    {
        /** @var PaymentService $paymentService */
        $paymentService = app(PaymentService::class, [
            'payment' => $this->payment,
            'gateway' => PaymentGateway::PLACETOPAY->value,
        ]);
        $subscriptionUser = $this->payment->subscriptionUser;
        $payer = [
            'name' => $this->payment->name,
            'email' => $this->payment->email,
            'type_document' => $this->payment->type_document,
            'num_document' => $this->payment->num_document,
        ];

        $response = $paymentService->createCollect($payer, $subscriptionUser->token);
        $subscriptionStatus = ($response->status === PaymentStatus::APPROVED->value) ? Status::ACTIVE->name : Status::INACTIVE->name;

        $this->payment->update([
            'status' => $response->status,
            'request_id' => $response->processIdentifier,
        ]);

        UpdateSubscriptionUser::execute([
            'status' => $subscriptionStatus,
            'payment_id' => $this->payment->id,
        ], $subscriptionUser);

        ProcessSendEmail::dispatch(
            PaymentStatusEmail::class,
            $subscriptionUser->user,
            [
                'subscription' => $subscriptionUser->subscription,
                'status' => $response->status
            ]
        );
        if ($response->status === PaymentStatus::REJECTED->value) {
            throw new \Exception('Payment rejected');
        }
    }

    public function tries(): int
    {
        $attempts = Setting::where('key', 'attempts')->first();
        return $attempts->value ?? config('queue.job.tries');
    }

    public function backoff(): int
    {
        $backoff = Setting::where('key', 'period_time')->first();
        return $backoff->value ?? config('queue.job.backoff');
    }
}
