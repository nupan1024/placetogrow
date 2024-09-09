<?php

namespace App\Domain\Fields\ViewModels;

use App\Domain\Fields\Models\Field;
use App\Support\Definitions\FieldsAttributes;
use App\Support\Definitions\FieldsTypes;
use App\Support\ViewModels\ViewModel;

class CreateViewFields extends ViewModel
{
    public function __construct()
    {
        parent::__construct(new Field());
    }
    public function toArray(): array
    {
        return [
            'types' => FieldsTypes::toArray(),
            'attributes' => FieldsAttributes::toArray(),
        ];
    }
}
