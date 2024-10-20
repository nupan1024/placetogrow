<?php

namespace App\Support\Services\Mail\Payment;

use App\Domain\Users\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentStatusEmail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(private readonly User $user, private readonly array $params = [])
    {
    }

    public function build(): self
    {

        return $this->subject('Se ha realizado el cobro de tu suscripción')
            ->view('emails.payments.paymentStatus')
            ->with([
                'user' => $this->user,
                'status' => $this->params['status'],
                'subscription' => $this->params['subscription'],
            ]);
    }
}
