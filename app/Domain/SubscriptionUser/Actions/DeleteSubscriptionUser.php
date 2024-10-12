<?php

namespace App\Domain\SubscriptionUser\Actions;

use App\Contracts\PaymentService;
use App\Domain\Payments\Models\Payment;
use App\Support\Actions\Action;
use App\Support\Definitions\PaymentGateway;
use Illuminate\Support\Facades\Log;

class DeleteSubscriptionUser implements Action
{
    public static function execute(array $params = [], $model = null): bool
    {
        try {
            $payment = Payment::find($model->payment_id);
            /** @var PaymentService $paymentService */
            $paymentService = app(PaymentService::class, [
                'payment' => $payment,
                'gateway' => PaymentGateway::PLACETOPAY->value,
            ]);
            $placetopay = $paymentService->deleteSubscription($model->token);

            if (!$placetopay['status']) {
                Log::channel('Subscriptions')->error('Error placetopay subscription: '.$placetopay['message']);
                return false;
            }
            return $model->delete();
        } catch (\Exception $e) {
            Log::channel('Subscriptions')->error('Error deleting subscription user: '.$e->getMessage());
            return false;
        }
    }
}
