<?php

namespace App\Support\Services\Mail\Subscription;

use App\Domain\Users\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriptionEmail extends Mailable
{
    use Queueable;
    use SerializesModels;
    public function __construct(private readonly User $user, private readonly array $params = [])
    {
    }
    public function build(): SubscriptionEmail
    {
        return $this->subject(__('subscriptions.updated_subject'))
            ->view('emails.subscriptions.updatedSubscription')
            ->with([
                'name_user' => $this->user->name,
                'subscription' => $this->params['subscription'],
                'params' => $this->params['dirtyData'],
            ]);
    }
}
