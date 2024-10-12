<?php

namespace App\Support\Observers;

use App\Domain\Subscriptions\Models\Subscription;
use App\Jobs\ProcessSendEmail;
use App\Support\Services\Mail\Subscription\SubscriptionEmail;

class SubscriptionObserver
{
    public function updated(Subscription $subscription): void
    {
        $subscriptionUsers = $subscription->subscribers()->get();
        if (count($subscriptionUsers) > 0) {
            dispatch(new ProcessSendEmail(SubscriptionEmail::class, $subscriptionUsers, $subscription->getDirty()));
        }
    }
}
