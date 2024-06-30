<?php

namespace App\Domain\Microsites\Actions;

use App\Domain\Microsites\Models\Microsite;
use App\Support\Actions\Action;
use Illuminate\Support\Facades\Storage;

class UpdateMicrosite implements Action
{
    public static function execute(array $params): bool
    {
        try {
            $microsite = Microsite::find($params['id']);
            $microsite->microsites_type_id = $params['fields']['microsites_type_id'];
            $microsite->category_id = $params['fields']['category_id'];
            $microsite->name = $params['fields']['name'];
            $microsite->description = $params['fields']['description'];
            $microsite->currency_id = $params['fields']['currency_id'];
            $microsite->date_expire_pay = $params['fields']['date_expire_pay'] ?? null;
            $microsite->status = $params['fields']['status'];

            if ($params['fields']['logo_path']) {
                if ($microsite->logo_path) {
                    Storage::disk('public')->delete($microsite->logo_path);
                }

                $microsite->logo_path = Storage::disk('public')->putFile('microsites_logo', $params['fields']['logo_path']);
            }
            return $microsite->save();
        } catch (\Exception $e) {
            logger()->error($e->getMessage());
            return false;
        }
    }
}
