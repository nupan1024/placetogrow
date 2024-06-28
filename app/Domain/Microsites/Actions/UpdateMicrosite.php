<?php

namespace App\Domain\Microsites\Actions;

use App\Domain\Microsites\Models\Microsite;
use App\Support\Actions\Action;

class UpdateMicrosite implements Action {

    public static function execute(array $params): bool {
        $response = false;

        try {
            $microsite = Microsite::find($params['id']);
            $microsite->microsites_type_id = $params['microsites_type_id'];
            $microsite->category_id = $params['category_id'];
            $microsite->name = $params['name'];
            $microsite->logo_path = $params['logo_path'];
            $microsite->description = $params['description'];
            $microsite->currency_id = $params['currency_id'];
            $microsite->date_expire_pay = $params['date_expire_pay'];
            $microsite->status = $params['status'];
            $response = $microsite->save();
        } catch (\Exception $e) {
            logger()->error($e->getMessage());
        }

        return $response;
    }

}
