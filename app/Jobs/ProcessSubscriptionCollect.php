<?php

namespace App\Jobs;

use App\Contracts\PaymentService;
use App\Domain\Payments\Actions\CreatePayment;
use App\Domain\SubscriptionUser\Actions\UpdateSubscriptionUser;
use App\Domain\SubscriptionUser\Models\SubscriptionUser;
use App\Support\Definitions\PaymentGateway;
use App\Support\Definitions\PaymentStatus;
use App\Support\Definitions\Status;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessSubscriptionCollect implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function handle(): void
    {
        SubscriptionUser::where('status', Status::ACTIVE->name)
            ->chunk(100, function (Collection $subscriptionUsers) {
                foreach ($subscriptionUsers as $subscriptionUser) {
                    $dataLastCollect = $subscriptionUser->data_last_collect;
                    $date = Carbon::parse($dataLastCollect['date_last_collect']);
                    $date = $date->addDays($dataLastCollect['billing_frequency'])->format('Y-m-d');

                    if ($date == now()->format('Y-m-d')) {
                        $params = [
                            'name' => $subscriptionUser->user->name,
                            'email' => $subscriptionUser->user->email,
                            'value' => $dataLastCollect['amount'],
                            'subscription_id' => $subscriptionUser->subscription->id,
                            'type_document' => "CC",
                            'num_document' => "123455",
                            'user_id' => $subscriptionUser->user->id,
                            'microsite_id' => $subscriptionUser->subscription->microsite_id,
                        ];
                        $payment = CreatePayment::execute($params);

                        /** @var PaymentService $paymentService */
                        $paymentService = app(PaymentService::class, [
                            'payment' => $payment,
                            'gateway' => PaymentGateway::PLACETOPAY->value,
                        ]);

                        $response = $paymentService->createCollect($params, $subscriptionUser->token);

                        $status = ($response->status === PaymentStatus::APPROVED->value) ? Status::ACTIVE->name : Status::INACTIVE->name;


                        UpdateSubscriptionUser::execute([
                            'status' => $status,
                            'payment_id' => $payment->id,
                        ], $subscriptionUser);

                        $payment->update([
                            'status' => $response->status,
                            'request_id' => $response->processIdentifier,
                        ]);
                    }
                }
            });
    }
}
