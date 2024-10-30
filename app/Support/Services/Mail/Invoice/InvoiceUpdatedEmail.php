<?php

namespace App\Support\Services\Mail\Invoice;

use App\Domain\Users\Models\User;
use App\Support\Definitions\StatusInvoices;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceUpdatedEmail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(private readonly User $user, private readonly array $params = [])
    {
    }

    public function build(): InvoiceUpdatedEmail
    {
        return $this->subject('Tu factura ha sido actualizada')
            ->view('emails.invoices.invoiceUpdated')
            ->with([
                'user' => $this->user,
                'invoice' => $this->params['invoice'],
                'data' => $this->params['data'],
                'paid_status' => StatusInvoices::PAID->value,
                'expired_status' => StatusInvoices::EXPIRED->value,
            ]);
    }
}
