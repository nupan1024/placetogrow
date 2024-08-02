<?php

namespace App\Domain\Fields\Actions;

use App\Domain\Fields\Models\Field;
use App\Support\Actions\Action;
use Illuminate\Support\Facades\Log;

class CreateField implements Action
{
    public static function execute(array $params): bool
    {
        try {
            $field = new Field();
            $field->name = $params['name'];
            $field->type = $params['type'];
            $field->label = $params['label'];
            $field->attributes = 'required';

            return $field->save();
        } catch (\Exception $e) {
            Log::channel('MicrositesAdmin')->error('Error creating microsite: '.$e->getMessage());

            return false;
        }
    }
}
