<?php

namespace App\Support\Observers;

use App\Domain\Subscriptions\Models\Subscription;
use App\Jobs\ProcessSendEmail;

class SubscriptionObserver
{
    public function updated(Subscription $subscription): void
    {
        $subscriptionUsers = $subscription->subscribers()->get();
        if (count($subscriptionUsers) > 0) {
            dispatch(new ProcessSendEmail('updated_subscription', $subscriptionUsers, [], $subscription->getDirty()));
        }
    }
}
