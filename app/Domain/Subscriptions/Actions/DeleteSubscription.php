<?php

namespace App\Domain\Subscriptions\Actions;

use App\Support\Actions\Action;
use Illuminate\Support\Facades\Log;

class DeleteSubscription implements Action
{
    public static function execute(array $params = [], $model = null): bool
    {
        try {
            return $model->delete();
        } catch (\Exception $e) {
            Log::channel('Subscriptions')->error('Error deleting subscription: '.$e->getMessage());

            return false;
        }
    }
}
