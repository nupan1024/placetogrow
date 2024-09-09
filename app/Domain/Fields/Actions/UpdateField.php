<?php

namespace App\Domain\Fields\Actions;

use App\Support\Actions\Action;
use Illuminate\Support\Facades\Log;

class UpdateField implements Action
{
    public static function execute(array $params = [], $model = null): bool
    {
        try {
            $model->type = $params['type'];
            $model->label = $params['label'];
            $model->attributes = $params['attributes'] ?? [];

            return $model->save();
        } catch (\Exception $e) {
            Log::channel('Fields')->error('Error updating field: '.$e->getMessage());

            return false;
        }
    }
}
