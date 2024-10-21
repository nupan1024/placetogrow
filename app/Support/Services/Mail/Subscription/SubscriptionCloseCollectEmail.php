<?php

namespace App\Support\Services\Mail\Subscription;

use App\Domain\Users\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriptionCloseCollectEmail extends Mailable
{
    use Queueable;
    use SerializesModels;
    public function __construct(private readonly User $user, private readonly array $params = [])
    {
    }
    public function build(): SubscriptionCloseCollectEmail
    {
        return $this->subject('Mañana se cobra tu suscripción')
            ->view('emails.subscriptions.subscriptionCloseCollect')
            ->with([
                'name_user' => $this->user->name,
                'subscription' => $this->params['subscription'],
            ]);
    }
}
