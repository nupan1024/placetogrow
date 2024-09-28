<?php

namespace App\Domain\Microsites\Actions;

use App\Domain\Microsites\Models\Microsite;
use App\Support\Actions\Action;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CreateMicrosite implements Action
{
    public static function execute(array $params = [], $model = null): bool
    {
        try {
            $microsite = new Microsite();
            $microsite->microsites_type_id = $params['microsites_type_id'];
            $microsite->category_id = $params['category_id'];
            $microsite->name = $params['name'];
            $microsite->logo_path = Storage::disk('public')->putFile('microsites_logo', $params['logo_path']);
            $microsite->description = $params['description'];
            $microsite->currency_id = $params['currency_id'];
            $microsite->status = $params['status'];
            $microsite->fields = $params['fields'] ?? [];
            $result = $microsite->save();

            if ($result) {
                Cache::forget(config('cache.stores.key.microsites_admin'));
                Cache::forget(config('cache.stores.key.microsites'));
            }

            return $result;
        } catch (\Exception $e) {
            Log::channel('MicrositesAdmin')->error('Error creating microsite: '.$e->getMessage());

            return false;
        }
    }
}
