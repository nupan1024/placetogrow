<?php

namespace App\Domain\SubscriptionUser\Actions;

use App\Support\Actions\Action;
use Illuminate\Support\Facades\Log;

class UpdateSubscriptionUser implements Action
{
    public static function execute(array $params = [], $model = null): bool
    {
        try {
            $model->token = $params['token'];
            $model->status = $params['status'];
            return $model->save();
        } catch (\Exception $e) {
            Log::channel('Subscriptions')->error('Error creating subscription: '.$e->getMessage());

            return false;
        }
    }
}
