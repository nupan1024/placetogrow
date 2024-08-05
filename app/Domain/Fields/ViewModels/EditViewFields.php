<?php

namespace App\Domain\Fields\ViewModels;

use App\Support\Definitions\FieldsAttributes;
use App\Support\Definitions\FieldsTypes;
use App\Support\ViewModels\ViewModel;

class EditViewFields extends ViewModel
{
    public function toArray(): array
    {
        return [
            'attributes' => FieldsAttributes::toArray(),
            'types' => FieldsTypes::toArray(),
            'field' => $this->model(),
        ];
    }
}
