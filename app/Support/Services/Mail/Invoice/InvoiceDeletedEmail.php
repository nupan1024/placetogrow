<?php

namespace App\Support\Services\Mail\Invoice;

use App\Domain\Users\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceDeletedEmail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(private readonly User $user, private readonly array $params = [])
    {
    }

    public function build(): InvoiceDeletedEmail
    {
        return $this->subject(__('invoices.delete_invoices_email', ['code' => $this->params['code']]))
            ->view('emails.invoices.invoiceDeleted')
            ->with([
                'user' => $this->user,
                'invoice' => $this->params,
            ]);
    }
}
