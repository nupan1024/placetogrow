<?php

namespace App\Support\Services\Mail;

use App\Domain\Subscriptions\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriptionEmail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public Subscription $subscription;
    public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription;
    }
    public function build(): SubscriptionEmail
    {
        return $this->subject(__('subscriptions.updated_subject'))
            ->view('emails.updatedSubscription')
            ->with([
                'name' => 'Name test',
                'name_subscription' => $this->subscription->name,
            ]);
    }
}
