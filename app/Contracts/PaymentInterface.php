<?php

namespace App\Contracts;

use App\Domain\Transactions\Models\Transaction;
use Dnetix\Redirection\Message\RedirectResponse;
use Dnetix\Redirection\PlacetoPay;

abstract class PaymentInterface
{
    abstract public function init(): PlacetoPay;

    abstract public function pay(array $payment): RedirectResponse|bool;

    abstract public function getPaymentStatus(Transaction $transaction): array;
}
