<?php

namespace App\Support\Services\Mail\Invoice;

use App\Domain\Users\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceCreatedEmail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(private readonly User $user, private readonly array $params = [])
    {
    }

    public function build(): InvoiceCreatedEmail
    {
        return $this->subject('Se ha generado una nueva Factura')
            ->view('emails.invoices.invoiceCreated')
            ->with([
                'user' => $this->user,
                'invoice' => $this->params['invoice'],
            ]);
    }
}
