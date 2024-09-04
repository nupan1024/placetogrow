<?php

namespace App\Domain\Subscriptions\Actions;

use App\Domain\Subscriptions\Models\Subscription;
use App\Support\Actions\Action;
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
            $subscription->time_expire = $params['time_expire'];
            $subscription->billing_frequency = $params['billing_frequency'];
            $subscription->currency_id = $params['currency_id'];
            return $subscription->save();
        } catch (\Exception $e) {
            Log::channel('Subscriptions')->error('Error creating subscription: '.$e->getMessage());

            return false;
        }
    }
}
