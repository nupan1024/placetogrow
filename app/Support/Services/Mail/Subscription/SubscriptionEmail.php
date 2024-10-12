<?php

namespace App\Support\Services\Mail\Subscription;

use App\Domain\SubscriptionUser\Models\SubscriptionUser;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriptionEmail extends Mailable
{
    use Queueable;
    use SerializesModels;
    public function __construct(private readonly SubscriptionUser $subscriptionUser, private readonly array $params = [])
    {
    }
    public function build(): SubscriptionEmail
    {
        return $this->subject(__('subscriptions.updated_subject'))
            ->view('emails.subscriptions.updatedSubscription')
            ->with([
                'name_user' => $this->subscriptionUser->user->name,
                'subscription' => $this->subscriptionUser->subscription,
                'params' => $this->params,
            ]);
    }
}
