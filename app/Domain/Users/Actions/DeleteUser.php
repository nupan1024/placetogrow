<?php

namespace App\Domain\Users\Actions;

use App\Support\Actions\Action;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DeleteUser implements Action
{
    public static function execute(array $params = [], $model = null): bool
    {
        try {
            DB::table('model_has_roles')
                ->where('model_id', $model->id)
                ->delete();
            $response = $model->delete();
            if ($response) {
                Cache::forget(config('cache.stores.key.users'));
            }

            return $response;
        } catch (\Exception $e) {
            Log::channel('Users')->error('Error deleting user: '.$e->getMessage());

            return false;
        }
    }

}
