<?php

namespace App\Domain\Dashboard\ViewModels;

use App\Domain\Dashboard\Actions\GetDonationsPayments;
use App\Domain\Dashboard\Actions\GetInvoicesPayments;
use App\Domain\Dashboard\Actions\GetSubscriptionsPayments;
use App\Domain\Dashboard\Actions\GetTotalActiveUsers;
use App\Domain\Dashboard\Actions\GetTotalByExpiredPendingInvoices;
use App\Domain\Dashboard\Actions\GetTotalByPaidPendingInvoices;
use App\Domain\Dashboard\Actions\GetTotalMicrositeTypes;
use App\Domain\Invoices\Models\Invoice;
use App\Support\ViewModels\ViewModel;

class ViewDashboard extends ViewModel
{
    public function __construct()
    {
        parent::__construct(new Invoice());
    }

    public function toArray(): array
    {
        return [
            'total_active_users' => GetTotalActiveUsers::execute(),
            'total_microsites' => GetTotalMicrositeTypes::execute(),
            'paid_pending_invoices' => GetTotalByPaidPendingInvoices::execute(),
            'expired_pending_invoices' => GetTotalByExpiredPendingInvoices::execute(),
            'invoices_payments_by_microsite_type' => GetInvoicesPayments::execute(),
            'subscriptions_payments_by_microsite_type' => GetSubscriptionsPayments::execute(),
            'donations_payments_by_microsite_type' => GetDonationsPayments::execute(),
        ];
    }

}
