<?php

namespace App\Domain\SubscriptionUser\Actions;

use App\Domain\SubscriptionUser\Models\SubscriptionUser;
use App\Support\Actions\Action;
use App\Support\Definitions\Status;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ValidateIfSubscriptionExist implements Action
{
    public static function execute(array $params = [], $model = null): bool
    {
        try {
            $user_id = (!is_null(Auth::user())) ? Auth::user()->id : null;
            $subscriptionUser = SubscriptionUser::where('user_id', $params['user_id'] ?? $user_id)
                ->where('subscription_id', $params['subscription_id'])
                ->where('status', Status::ACTIVE->name)
                ->first();
            return !$subscriptionUser;
        } catch (\Exception $e) {
            Log::channel('Subscriptions')->error('Error creating subscription: '.$e->getMessage());

            return false;
        }
    }
}
