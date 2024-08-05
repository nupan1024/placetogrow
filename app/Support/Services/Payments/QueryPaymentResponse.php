<?php

namespace App\Support\Services\Payments;

use App\Support\Definitions\PaymentStatus;
use Illuminate\Contracts\Support\Arrayable;

class QueryPaymentResponse implements Arrayable
{
    public PaymentStatus $status;

    public function __construct(
        public readonly string $reason,
        string $status
    ) {
        $this->status = PaymentStatus::tryFrom($status) ?? PaymentStatus::UNKNOWN;
    }

    public function toArray(): array
    {
        return [
            'reason' => $this->reason,
            'status' => $this->status->value,
        ];
    }
}
