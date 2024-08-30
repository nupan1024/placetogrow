<?php

namespace App\Domain\Fields\Actions;

use App\Domain\Microsites\Models\Microsite;
use App\Support\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

class DeleteField implements Action
{
    public static function execute(array $params = [], $model = null): bool
    {
        try {
            $microsites = Microsite::where(function (Builder $query) use ($model) {
                $query->whereJsonContains('fields', $model->name);
            })->get();
            if (count($microsites) > 0) {
                Log::channel('Fields')
                    ->error(
                        'Error deleting field: Microsite has this field, total microsites: '
                        .$microsites->count().', name: '.$model->name
                    );
                return false;
            }

            return $model->delete();
        } catch (\Exception $e) {
            Log::channel('Fields')
                ->error('Error deleting field: '.$e->getMessage());

            return false;
        }
    }

}
