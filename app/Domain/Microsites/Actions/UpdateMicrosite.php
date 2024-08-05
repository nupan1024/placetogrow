<?php

namespace App\Domain\Microsites\Actions;

use App\Support\Actions\Action;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UpdateMicrosite implements Action
{
    public static function execute(array $params): bool
    {
        try {
            $params['microsite']->category_id = $params['fields']['category_id'];
            $params['microsite']->name = $params['fields']['name'];
            $params['microsite']->description = $params['fields']['description'];
            $params['microsite']->currency_id = $params['fields']['currency_id'];
            $params['microsite']->date_expire_pay = $params['fields']['date_expire_pay'] ?? null;
            $params['microsite']->status = $params['fields']['status'];
            $params['microsite']->fields = $params['fields']['fields'] ?? [];
            if ($params['fields']['logo_path']) {
                if ($params['microsite']->logo_path) {
                    Storage::disk('public')->delete($params['microsite']->logo_path);
                }

                $params['microsite']->logo_path = Storage::disk('public')->putFile('microsites_logo', $params['fields']['logo_path']);
            }

            return $params['microsite']->save();
        } catch (\Exception $e) {
            Log::channel('MicrositesAdmin')->error('Error updating microsite: '.$e->getMessage());

            return false;
        }
    }
}
