<?php

namespace App\Domain\Fields\Actions;

use App\Support\Actions\Action;
use Illuminate\Support\Facades\Log;

class UpdateField implements Action
{
    public static function execute(array $params): bool
    {
        try {
            $params['field']->type = $params['data']['type'];
            $params['field']->label = $params['data']['label'];
            $params['field']->attributes = $params['data']['attributes'] ?? [];

            return $params['field']->save();
        } catch (\Exception $e) {
            Log::channel('Fields')->error('Error updating field: '.$e->getMessage());

            return false;
        }
    }
}
