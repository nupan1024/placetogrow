<?php

namespace App\Domain\Subscriptions\Actions;

use App\Support\Actions\Action;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class UpdateSubscription implements Action
{
    public static function execute(array $params = [], $model = null): bool
    {
        try {
            $model->name = $params['name'];
            $model->amount = $params['amount'];
            $model->description = $params['description'];
            $model->status = $params['status'];
            $model->billing_frequency = $params['billing_frequency'];
            $result = $model->save();

            if ($result) {
                Cache::forget(config('cache.stores.key.subscriptions_admin'));
            }

            return $result;
        } catch (\Exception $e) {
            Log::channel('Subscriptions')->error('Error updating subscription: '.$e->getMessage());

            return false;
        }
    }
}
