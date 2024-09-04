<?php

namespace App\Domain\Subscriptions\Actions;

use App\Support\Actions\Action;
use Illuminate\Support\Facades\Log;

class UpdateSubscription implements Action
{
    public static function execute(array $params = [], $model = null): bool
    {
        try {
            $model->microsite_id = $params['microsite_id'];
            $model->name = $params['name'];
            $model->amount = $params['amount'];
            $model->description = $params['description'];
            $model->status = $params['status'];
            $model->time_expire = $params['time_expire'];
            $model->billing_frequency = $params['billing_frequency'];
            $model->currency_id = $params['currency_id'];
            return $model->save();
        } catch (\Exception $e) {
            Log::channel('Subscriptions')->error('Error updating subscription: '.$e->getMessage());

            return false;
        }
    }
}
