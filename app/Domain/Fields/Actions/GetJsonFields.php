<?php

namespace App\Domain\Fields\Actions;

use App\Domain\Fields\Models\Field;
use App\Support\Actions\Action;

class GetJsonFields implements Action
{
    public static function execute(array $params): array|bool
    {
        $json = [];
        foreach ($params as $field) {
            $json[] = Field::where('name', $field)->first()?->toArray();
        }
        return $json;
    }
}
