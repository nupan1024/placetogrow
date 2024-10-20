<?php

namespace App\Jobs;

use App\Domain\Payments\Actions\CreatePayment;
use App\Domain\Payments\Actions\UpdatePaymentWithPaymentTypes;
use App\Domain\SubscriptionUser\Models\SubscriptionUser;
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
                            'type_document' => $subscriptionUser->payment->type_document,
                            'num_document' => $subscriptionUser->payment->num_document,
                            'user_id' => $subscriptionUser->user->id,
                            'microsite_id' => $subscriptionUser->subscription->microsite_id,
                        ];
                        $payment = CreatePayment::execute($params);
                        UpdatePaymentWithPaymentTypes::execute([], $payment);
                    }
                }
            });
    }
}
