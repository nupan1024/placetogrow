<?php

namespace App\Domain\Microsites\Actions;

use App\Domain\Microsites\Models\Microsite;
use App\Support\Actions\Action;
use Illuminate\Support\Facades\Storage;

class CreateMicrosite implements Action
{
    public static function execute(array $params): bool
    {
        try {
            $microsite = new Microsite();
            $microsite->microsites_type_id = $params['microsites_type_id'];
            $microsite->category_id = $params['category_id'];
            $microsite->name = $params['name'];
            $microsite->logo_path = Storage::disk('public')->putFile('microsites_logo', $params['logo_path']);
            $microsite->description = $params['description'];
            $microsite->currency_id = $params['currency_id'];
            $microsite->date_expire_pay = $params['date_expire_pay'][0];
            $microsite->status = $params['status'];

            return $microsite->save();
        } catch (\Exception $e) {
            logger()->error($e->getMessage());

            return false;
        }
    }
}
