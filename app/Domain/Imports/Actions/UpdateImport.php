<?php

namespace App\Domain\Imports\Actions;

use App\Support\Actions\Action;
use Illuminate\Support\Facades\Log;

class UpdateImport implements Action
{
    public static function execute(array $params = [], $model = null): bool
    {
        try {
            $model->errors = $params['errors'] ?? $model->errors;
            $model->status = $params['status'] ?? $model->status;
            return $model->save();
        } catch (\Exception $e) {
            Log::channel('Imports')->error('Error updating import: '.$e->getMessage());
            return false;
        }
    }
}
