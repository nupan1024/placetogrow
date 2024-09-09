<?php

namespace App\Domain\Imports\Actions;

use App\Domain\Imports\Models\Import;
use App\Support\Actions\Action;
use App\Support\Definitions\ImportStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CreateImport implements Action
{
    public static function execute(array $params = [], $model = null): bool|Import
    {
        try {
            $file = $params['file'];
            $path = $file->store(options: ['disk' => Import::DISK]);

            $import = new Import();
            $import->path = $path;
            $import->file_name = $file->getClientOriginalName();
            $import->status = ImportStatus::PENDING->value;
            $import->user_id = Auth::user()->id;
            $import->save();

            return $import;
        } catch (\Exception $e) {
            Log::channel('Imports')->error('Error creating import: '.$e->getMessage());
            return false;
        }
    }
}
