<?php

namespace App\Contracts;

use App\Support\Services\Payments\PaymentResponse;

interface PaymentService
{
    public function create(array $buyer): PaymentResponse;
}
