<?php

namespace App\Contracts;

use App\Domain\Payments\Models\Payment;
use App\Support\Services\Payments\PaymentResponse;
use App\Support\Services\Payments\QueryPaymentResponse;

interface PaymentService
{
    public function setPayment(Payment $payment): void;
    public function create(array $buyer): PaymentResponse;
    public function createCollect(array $payer, string $token): PaymentResponse;
    public function deleteSubscription(string $token): array;
    public function getPaymentStatus(Payment $payment): QueryPaymentResponse;
    public function getToken(Payment $payment): array;
}
