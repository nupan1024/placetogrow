<?php

namespace App\Domain\Microsites\Actions;

use App\Support\Actions\Action;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UpdateMicrosite implements Action
{
    public static function execute(array $params = [], $model = null): bool
    {
        try {
            $model->category_id = $params['category_id'];
            $model->name = $params['name'];
            $model->description = $params['description'];
            $model->currency_id = $params['currency_id'];
            $model->date_expire_pay = $params['date_expire_pay'] ?? null;
            $model->status = $params['status'];
            $model->fields = $params['fields'] ?? [];
            if ($params['logo_path']) {
                if ($model->logo_path) {
                    Storage::disk('public')->delete($model->logo_path);
                }

                $model->logo_path = Storage::disk('public')->putFile('microsites_logo', $params['logo_path']);
            }
            $result = $model->save();

            if ($result) {
                Cache::forget(config('cache.stores.key.microsites_admin'));
                Cache::forget(config('cache.stores.key.microsites'));
            }

            return $result;
        } catch (\Exception $e) {
            Log::channel('MicrositesAdmin')->error('Error updating microsite: '.$e->getMessage());

            return false;
        }
    }
}
