<?php

namespace App\Domain\Fields\Actions;

use App\Domain\Fields\Models\Field;
use App\Support\Actions\Action;
use Illuminate\Support\Facades\Log;

class GetJsonFields implements Action
{
    public static function execute(array $params): array|bool
    {
        try {
            $json = [];
            foreach ($params as $field) {
                $json[] = Field::where('name', $field)->first()?->toArray();
            }
            return $json;
        } catch (\Exception $e) {
            Log::channel('Fields')->error('Error creating the json: '.$e->getMessage());

            return false;
        }
    }
}
