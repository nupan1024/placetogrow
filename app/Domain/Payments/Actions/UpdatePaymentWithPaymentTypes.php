<?php

namespace App\Domain\Payments\Actions;

use App\Contracts\PaymentService;
use App\Domain\Invoices\Models\Invoice;
use App\Domain\SubscriptionUser\Actions\UpdateSubscriptionUser;
use App\Domain\SubscriptionUser\Models\SubscriptionUser;
use App\Support\Actions\Action;
use App\Support\Definitions\PaymentGateway;

class UpdatePaymentWithPaymentTypes implements Action
{
    public static function execute(array $params = [], $model = null): bool
    {
        /** @var PaymentService $paymentService */
        $paymentService = app(PaymentService::class, [
            'payment' => $model,
            'gateway' => PaymentGateway::PLACETOPAY->value,
        ]);

        $data = $paymentService->getPaymentStatus($model);
        $status = $data->status->value ?? $model->status;
        if (is_numeric($model->invoice_id)) {
            $invoice = Invoice::find($model->invoice_id);
            $invoice->status = $status;
            $invoice->save();
        }

        if (is_numeric($model->subscription_id)) {
            $dataToken = $paymentService->getToken($model);
            if ($dataToken['status']) {
                $payer = [
                    'name' => $model->name,
                    'email' => $model->email,
                    'type_document' => $model->type_document,
                    'num_document' => $model->num_document,
                ];
                $payment = $paymentService->createCollect($payer, $dataToken['token']);
            }

            $subscriptionUser = SubscriptionUser::where('subscription_id', $model->subscription_id)
                ->where('user_id', $model->user_id)
                ->first();

            UpdateSubscriptionUser::execute([
                'token' => $dataToken['token'] ?? null,
                'status' => $payment['status_payment'] ?? $subscriptionUser->status,
            ], $subscriptionUser);

            $status = $payment['status_payment'] ?? $subscriptionUser->status;
        }

        $model->status = $status;
        return $model->save();
    }

}
