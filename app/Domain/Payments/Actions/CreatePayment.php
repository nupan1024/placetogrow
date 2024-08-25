<?php

namespace App\Domain\Payments\Actions;

use App\Domain\Payments\Models\Payment;
use App\Support\Actions\Action;
use App\Support\Definitions\PaymentGateway;
use App\Support\Definitions\PaymentStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CreatePayment implements Action
{
    public static function execute(array $params): Payment|bool
    {
        try {
            $payment = new Payment();
            $payment->name = $params['name'];
            $payment->email = $params['email'];
            $payment->value = $params['value'];
            $payment->fields = $params['fields'] ?? [];
            $payment->invoice_id = $params['invoice_id'] ?? null;
            $payment->type_document = $params['type_document'];
            $payment->num_document = $params['num_document'];
            $payment->user_id = (!is_null(Auth::user())) ? Auth::user()->id : null;
            $payment->microsite_id = $params['microsite_id'];
            $payment->status = PaymentStatus::PENDING->value;
            $payment->payment_type = PaymentGateway::PLACETOPAY->value;
            $payment->reference = 'PAYMENT_MICROSITE_'. date('ymdHis');
            $payment->save();
            return $payment;
        } catch (\Exception $e) {
            Log::channel('Payment')->error('Error creating payment: '.$e->getMessage());

            return false;
        }
    }
}
