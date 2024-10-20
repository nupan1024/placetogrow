<?php

namespace App\Domain\Settings\Actions;

use App\Domain\Settings\Models\Setting;
use App\Support\Actions\Action;
use Illuminate\Support\Facades\Log;

class UpdateSetting implements Action
{
    public static function execute(array $params = [], $model = null): bool
    {
        try {
            foreach ($params as $key => $value) {
                Setting::where('key', $key)->update(
                    ['value' => $value]
                );
            }
            return true;
        } catch (\Exception $e) {
            Log::channel('Settings')->error('Error updating setting: '.$e->getMessage());
            return false;
        }
    }
}
