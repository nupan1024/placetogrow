<?php

namespace App\Domain\SubscriptionUser\Actions;

use App\Support\Actions\Action;
use App\Support\Definitions\Status;
use Illuminate\Support\Facades\Log;

class UpdateSubscriptionUser implements Action
{
    public static function execute(array $params = [], $model = null): bool
    {
        try {
            $model->token = $params['token'] ?? $model->token;
            $model->payment_id = $params['payment_id'] ?? $model->payment_id;
            $model->status = $params['status'];

            if ($params['status'] === Status::ACTIVE->name || is_null($model->data_last_collect)) {
                $dataLastCollect = [
                    'date_last_collect' => now()->format('Y-m-d H:i:s'),
                    'amount' => $model->subscription->amount,
                    'billing_frequency' => $model->subscription->billing_frequency,
                ];

                $model->data_last_collect = $dataLastCollect;
            }
            return $model->save();
        } catch (\Exception $e) {
            Log::channel('Subscriptions')->error('Error creating subscription: '.$e->getMessage());

            return false;
        }
    }
}
