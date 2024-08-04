<?php

namespace App\Domain\Microsites\ViewModels;

use App\Domain\Fields\Actions\GetJsonFields;
use App\Support\Definitions\DocumentsTypes;
use App\Support\ViewModels\ViewModel;

class FormMicrosite extends ViewModel
{
    public function toArray(): array
    {
        $microsite = $this->model()->with(['category', 'type', 'currency'])->find($this->model()->id);
        return [
            'microsite' => $microsite,
            'fields' => GetJsonFields::execute($microsite->fields),
            'documents' => DocumentsTypes::toArray()
        ];
    }
}
