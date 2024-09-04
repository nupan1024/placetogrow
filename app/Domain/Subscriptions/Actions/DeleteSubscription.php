<?php

namespace App\Domain\Subscriptions\Actions;

use App\Support\Actions\Action;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class DeleteSubscription implements Action
{
    public static function execute(array $params = [], $model = null): bool
    {
        try {
            $result = $model->delete();

            if ($result) {
                Cache::forget(config('cache.stores.key.subscriptions_admin'));
            }

            return $result;
        } catch (\Exception $e) {
            Log::channel('Subscriptions')->error('Error deleting subscription: '.$e->getMessage());

            return false;
        }
    }
}
