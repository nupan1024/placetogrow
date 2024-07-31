<?php

namespace App\Contracts;

use App\Domain\Transactions\Models\Transaction;
use Dnetix\Redirection\Message\RedirectResponse;
use Dnetix\Redirection\PlacetoPay;

abstract class PaymentInterface
{
    abstract public function pay(array $payment): RedirectResponse|bool;

    abstract public function getPaymentStatus(Transaction $transaction): array;

    public function setUpPayment(): PlacetoPay
    {
        return new PlacetoPay([
            'login' => config('placetoplay.login'),
            'tranKey' => config('placetoplay.secret_key'),
            'baseUrl' => config('placetoplay.base_url'),
        ]);
    }
}
