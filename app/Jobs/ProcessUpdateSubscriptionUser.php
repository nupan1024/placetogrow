<?php

namespace App\Jobs;

use App\Domain\SubscriptionUser\Models\SubscriptionUser;
use App\Support\Definitions\PaymentStatus;
use App\Support\Definitions\StatusInvoices;
use App\Support\Services\Payments\Gateways\PlaceToPayService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Crypt;

class ProcessUpdateSubscriptionUser implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function handle(): void
    {
        SubscriptionUser::where('status', StatusInvoices::PENDING->name)
            ->chunk(100, function (Collection $subscriptionUsers) {
                /**
                 * @var PlaceToPayService $placetopay
                 */
                $placetopay = app(PlaceToPayService::class);
                foreach ($subscriptionUsers as $subscriptionUser) {
                    $data = $placetopay->init()->query($subscriptionUser->payment->request_id);
                    $token = "";
                    if ($data->status()->status() === PaymentStatus::APPROVED->value) {
                        $dataInstrument = $data->subscription()->instrumentToArray();
                        $token = Crypt::encryptString($dataInstrument[0]['value'] ?? $token);
                    }

                    $subscriptionUser->update([
                        'token' => $token,
                        'status' => $data->status()->status() ?: $subscriptionUser->status,
                    ]);
                }
            });
    }
}
