<?php

namespace App\Support\Services\Mail\Invoice;

use App\Domain\Users\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceExpiredEmail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(private readonly User $user, private readonly array $params = [])
    {
    }

    public function build(): self
    {
        return $this->subject('¡¡Tu factura se encuentra vencida!!')
            ->view('emails.invoices.invoiceExpired')
            ->with([
                'user' => $this->user,
                'invoice' => $this->params['invoice'],
            ]);
    }
}
