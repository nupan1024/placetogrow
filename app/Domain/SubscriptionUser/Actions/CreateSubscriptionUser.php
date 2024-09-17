<?php

namespace App\Domain\SubscriptionUser\Actions;

use App\Domain\SubscriptionUser\Models\SubscriptionUser;
use App\Support\Actions\Action;
use Illuminate\Support\Facades\Log;

class CreateSubscriptionUser implements Action
{
    public static function execute(array $params = [], $model = null): bool
    {
        try {
            $subscriptionUser = new SubscriptionUser();
            $subscriptionUser->user_id = $params['user_id'];
            $subscriptionUser->subscription_id = $params['subscription_id'];
            $subscriptionUser->status = $params['status'];
            $subscriptionUser->payment_id = $params['payment_id'];
            return $subscriptionUser->save();
        } catch (\Exception $e) {
            Log::channel('Subscriptions')->error('Error creating subscription: '.$e->getMessage());

            return false;
        }
    }
}
