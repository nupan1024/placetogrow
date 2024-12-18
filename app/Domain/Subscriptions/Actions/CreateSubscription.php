<?php

namespace App\Domain\Subscriptions\Actions;

use App\Domain\Subscriptions\Models\Subscription;
use App\Support\Actions\Action;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CreateSubscription implements Action
{
    public static function execute(array $params = [], $model = null): bool
    {
        try {
            $subscription = new Subscription();
            $subscription->microsite_id = $params['microsite_id'];
            $subscription->name = $params['name'];
            $subscription->amount = $params['amount'];
            $subscription->description = $params['description'];
            $subscription->status = $params['status'];
            $subscription->time_expire = Carbon::now()->addYears(5);
            $subscription->billing_frequency = $params['billing_frequency'];
            $subscription->currency_id = $params['currency_id'];
            $result = $subscription->save();

            if ($result) {
                Cache::forget(config('cache.stores.key.subscriptions_admin'));
            }

            return $result;
        } catch (\Exception $e) {
            Log::channel('Subscriptions')->error('Error creating subscription: '.$e->getMessage());

            return false;
        }
    }
}
