<?php

namespace App\Domain\Users\Actions;

use App\Support\Actions\Action;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DeleteUser implements Action
{
    public static function execute(array $params = [], $model = null): bool
    {
        try {
            DB::table('model_has_roles')
                ->where('model_id', $model->id)
                ->delete();
            return $model->delete();
        } catch (\Exception $e) {
            Log::channel('Users')->error('Error deleting user: '.$e->getMessage());

            return false;
        }
    }

}
