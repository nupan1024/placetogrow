<?php

namespace App\Domain\Invoices\Actions;

use App\Support\Actions\Action;
use Illuminate\Support\Facades\Log;

class UpdateInvoice implements Action
{
    public static function execute(array $params = [], $model = null): bool
    {
        try {
            $model->microsite_id = $params['microsite_id'];
            $model->user_id = $params['user_id'];
            $model->value = $params['value'];
            $model->description = $params['description'];
            $model->date_expire_pay = $params['date_expire_pay'];

            return $model->save();
        } catch (\Exception $e) {
            Log::channel('Invoices')->error('Error updating invoice: '.$e->getMessage());

            return false;
        }
    }
}
