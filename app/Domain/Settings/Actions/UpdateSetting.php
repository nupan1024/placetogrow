<?php

namespace App\Domain\Settings\Actions;

use App\Support\Actions\Action;
use Illuminate\Support\Facades\Log;

class UpdateSetting implements Action
{
    public static function execute(array $params = [], $model = null): bool
    {
        try {
            $model->value = $params['value'];
            return $model->save();
        } catch (\Exception $e) {
            Log::channel('Settings')->error('Error updating setting: '.$e->getMessage());

            return false;
        }
    }
}
