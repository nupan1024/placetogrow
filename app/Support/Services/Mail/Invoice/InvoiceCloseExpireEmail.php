<?php

namespace App\Support\Services\Mail\Invoice;

use App\Domain\Users\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceCloseExpireEmail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(private readonly User $user, private readonly array $params = [])
    {
    }

    public function build(): self
    {

        return $this->subject('¡¡Tu factura vence mañana!!')
            ->view('emails.invoices.invoiceCloseExpire')
            ->with([
                'user' => $this->user,
                'invoice' => $this->params['invoice'],
            ]);
    }
}
