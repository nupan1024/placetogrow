<?php

namespace App\Domain\Fields\Actions;

use App\Domain\Microsites\Models\Microsite;
use App\Support\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

class DeleteField implements Action
{
    public static function execute(array $params): bool
    {
        try {
            $microsites = Microsite::where(function (Builder $query) use ($params) {
                $query->whereJsonContains('fields', $params['field']->name);
            })->get();
            if (count($microsites) > 0) {
                Log::channel('Fields')
                    ->error(
                        'Error deleting field: Microsite has this field, total microsites: '
                        .$microsites->count().', name: '.$params['field']->name
                    );
                return false;
            }

            return $params['field']->delete();
        } catch (\Exception $e) {
            Log::channel('Fields')
                ->error('Error updating field: '.$e->getMessage());

            return false;
        }
    }

}
