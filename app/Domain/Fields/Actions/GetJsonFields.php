<?php

namespace App\Domain\Fields\Actions;

use App\Domain\Fields\Models\Field;
use App\Support\Actions\Action;
use Illuminate\Support\Facades\Log;

class GetJsonFields implements Action
{
    public static function execute(array $params): false|string
    {
        try {
            $json = [];

            foreach ($params as $field) {
                $fieldData = Field::where('name', $field)->first();
                $json[] = [
                    'name' => $field,
                    'label' => $fieldData->label,
                    'type' => $fieldData->type,
                    'attributes' => $fieldData->attributes,
                ];
            }
            return json_encode($json);
        } catch (\Exception $e) {
            Log::channel('MicrositesAdmin')->error('Error creating the json: '.$e->getMessage());

            return false;
        }
    }
}
