<?php

namespace App\Support\Services\Payments;

class PaymentResponse
{
    public function __construct(
        public string $processIdentifier,
        public string $url,
        public string $status,
    ) {
    }

    public function toArray(): array
    {
        return [
            'url' => $this->url,
            'process_identifier' => $this->processIdentifier,
            'status' => $this->status,
        ];
    }
}
