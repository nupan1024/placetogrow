<?php

namespace App\Domain\Users\Actions;

use App\Support\Actions\Action;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DeleteUser implements Action
{
    public static function execute(array $params): bool
    {
        try {
            DB::table('model_has_roles')
                ->where('model_id', $params['user']->id)
                ->delete();
            return $params['user']->delete();
        } catch (\Exception $e) {
            Log::channel('Users')->error('Error deleting microsite: '.$e->getMessage());

            return false;
        }
    }
}
