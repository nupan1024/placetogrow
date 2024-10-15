<?php

namespace App\Contracts;

use App\Domain\Payments\Models\Payment;
use App\Support\Services\Payments\PaymentResponse;
use App\Support\Services\Payments\QueryPaymentResponse;
use Dnetix\Redirection\PlacetoPay;

interface PaymentGateway
{
    public function init(): PlacetoPay;
    public function buyer(array $data): self;
    public function payer(array $data): self;
    public function payment(Payment $payment): self;
    public function instrument(string $token): self;
    public function subscription(Payment $payment): self;
    public function deleteSubscription(): array;
    public function processCollect(): array;
    public function process(): PaymentResponse;
    public function getPaymentStatus(Payment $payment): QueryPaymentResponse;
    public function getToken(Payment $payment): array;
}
