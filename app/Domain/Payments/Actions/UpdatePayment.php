<?php

namespace App\Domain\Payments\Actions;

use App\Domain\Invoices\Models\Invoice;
use App\Domain\SubscriptionUser\Actions\CreateSubscriptionUser;
use App\Support\Actions\Action;
use App\Support\Definitions\PaymentStatus;
use Illuminate\Support\Facades\Auth;

class UpdatePayment implements Action
{
    public static function execute(array $params = [], $model = null): bool
    {
        if (is_numeric($model->invoice_id)) {
            $invoice = Invoice::find($model->invoice_id);
            $invoice->status = $params['status'];
            $invoice->save();
        }

        if (is_numeric($model->subscription_id) && $params['status'] === PaymentStatus::APPROVED->value) {
            CreateSubscriptionUser::execute([
                'user_id' => Auth::user()->id ?? $model->subscription->user_id,
                'subscription_id' => $model->subscription_id,
                'status' => $params['status'],
                'payment_id' => $model->id,
            ]);
        }

        $model->process_url = $params['url'] ?? $model->process_url;
        $model->request_id = $params['request_id'] ?? $model->request_id;
        $model->status = $params['status'] ?? $model->status;
        return $model->save();
    }

}
