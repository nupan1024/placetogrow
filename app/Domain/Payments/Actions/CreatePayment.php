<?php

namespace App\Domain\Payments\Actions;

use App\Domain\Payments\Models\Payment;
use App\Support\Actions\Action;
use Illuminate\Support\Facades\Log;

class CreatePayment implements Action
{
    public static function execute(array $params): Payment|bool
    {
        try {
            $payment = new Payment();
            $payment->request_id = $params['payment']['requestId'];
            $payment->process_url = $params['payment']['processUrl'];
            $payment->payment_type = 'place_to_pay';
            $payment->status = null;
            $payment->transaction_id = $params['transaction_id'];
            $payment->save();
            return $payment;
        } catch (\Exception $e) {
            Log::channel('Payment')->error('Error creating payment: '.$e->getMessage());

            return false;
        }
    }
}
