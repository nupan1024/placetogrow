<?php

namespace App\Domain\Microsites\ViewModels;

use App\Domain\Categories\Models\Category;
use App\Domain\Currencies\Models\Currency;
use App\Domain\Fields\Models\Field;
use App\Domain\Microsites\Models\MicrositeType;
use App\Support\Definitions\MicrositesTypes;
use App\Support\Definitions\Status;
use App\Support\ViewModels\ViewModel;

class EditViewModel extends ViewModel
{
    public function toArray(): array
    {
        return [
            'categories' => Category::where('status', Status::ACTIVE->value)->get(),
            'microsites_types' => MicrositeType::where('status', Status::ACTIVE->value)->get(),
            'currencies' => Currency::where('status', Status::ACTIVE->value)->get(),
            'microsite' => $this->model()->getRawOriginal(),
            'states' => Status::asOptions(),
            'is_invoice' => $this->model()->getRawOriginal('microsites_type_id') === MicrositesTypes::INVOICE->value,
            'fields' => Field::select('name', 'label')->get(),
        ];
    }
}
