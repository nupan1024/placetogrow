<?php

namespace App\Domain\Payments\Actions;

use App\Contracts\PaymentService;
use App\Domain\Invoices\Models\Invoice;
use App\Domain\Settings\Models\Setting;
use App\Domain\SubscriptionUser\Actions\UpdateSubscriptionUser;
use App\Jobs\ProcessRetryPaymentSubscription;
use App\Jobs\ProcessSendEmail;
use App\Support\Actions\Action;
use App\Support\Definitions\PaymentGateway;
use App\Support\Definitions\PaymentStatus;
use App\Support\Definitions\Status;
use App\Support\Definitions\StatusInvoices;
use App\Support\Services\Mail\Payment\PaymentStatusEmail;

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
            $invoice->status = ($status === PaymentStatus::APPROVED->value) ? StatusInvoices::PAID->name : StatusInvoices::PENDING->name;
            $invoice->save();
        }

        if (is_numeric($model->subscription_id)) {
            $subscriptionUser = $params['subscriptionUser'] ?? $model->subscriptionUser;
            if ($subscriptionUser) {
                $token = $subscriptionUser->token;

                if (is_null($token)) {
                    $dataToken = $paymentService->getToken($model);
                    $token = ($dataToken['status']) ? $dataToken['token'] : $token;
                }

                $payer = [
                    'name' => $model->name,
                    'email' => $model->email,
                    'type_document' => $model->type_document,
                    'num_document' => $model->num_document,
                ];
                $payment = $paymentService->createCollect($payer, $token);
                $subscriptionStatus = ($payment->status === PaymentStatus::APPROVED->value) ? Status::ACTIVE->name : $subscriptionUser->status;

                UpdateSubscriptionUser::execute([
                    'token' => $dataToken['token'] ?? null,
                    'status' => $subscriptionStatus,
                    'payment_id' => $model->id,
                ], $subscriptionUser);

                $status = $payment->status;

                if ($payment->status === PaymentStatus::REJECTED->value) {
                    $backoff = Setting::where('key', 'period_time')->first();
                    ProcessRetryPaymentSubscription::dispatch($model)
                        ->delay(now()->addSeconds((int)$backoff->value));
                }

                ProcessSendEmail::dispatch(
                    PaymentStatusEmail::class,
                    $subscriptionUser->user,
                    [
                        'subscription' => $subscriptionUser->subscription,
                        'status' => $status
                    ]
                );
            }
        }

        $model->status = $status;
        $model->request_id = $payment->processIdentifier ?? $model->request_id;
        return $model->save();
    }

}
