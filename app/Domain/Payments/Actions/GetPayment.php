<?php

namespace App\Domain\Payments\Actions;

use App\Domain\Payments\Models\Payment;
use App\Support\Actions\Action;

class GetPayment implements Action
{
    public static function execute(array $params): Payment
    {
        return Payment::where('transaction_id', $params['transaction_id'])
            ->first();
    }

}
