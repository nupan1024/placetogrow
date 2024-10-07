<?php

namespace App\Support\Services\Payments;

use App\Contracts\PaymentGateway;
use App\Contracts\PaymentService as PaymentServiceContract;
use App\Domain\Payments\Models\Payment;

class PaymentService implements PaymentServiceContract
{
    public function __construct(
        protected Payment $payment,
        protected PaymentGateway $gateway,
    ) {
    }

    public function create(array $buyer): PaymentResponse
    {
        if (isset($this->payment->subscription_id)) {
            $response = $this->gateway->buyer($buyer)
                ->subscription($this->payment)
                ->process();
        } else {
            $response = $this->gateway->buyer($buyer)
                ->payment($this->payment)
                ->process();
        }


        $this->payment->update([
            'process_identifier' => $response->processIdentifier,
        ]);

        return $response;
    }

    public function deleteSubscription(string $token): array
    {
        return $this->gateway->deleteSubscription($token);
    }
}
