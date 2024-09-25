<?php

namespace App\Support\Observers;

use App\Domain\Subscriptions\Models\Subscription;
use App\Support\Services\Mail\SubscriptionEmail;
use Illuminate\Support\Facades\Mail;

class SubscriptionObserver
{
    public function updated(Subscription $subscription): void
    {
        Mail::to('nupan1024@gmail.com')->send(new SubscriptionEmail($subscription));
    }
}
