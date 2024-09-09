<?php

namespace App\Domain\Subscriptions\ViewModels;

use App\Domain\Currencies\Models\Currency;
use App\Domain\Microsites\Actions\GetMicrositesByStatusAndType;
use App\Support\Definitions\BillingFrequency;
use App\Support\Definitions\MicrositesTypes;
use App\Support\Definitions\Status;
use App\Support\ViewModels\ViewModel;

class EditViewSubscriptions extends ViewModel
{
    public function toArray(): array
    {
        return [
            'currencies' => Currency::all(),
            'states' => Status::asOptions(),
            'billing_frequency' => BillingFrequency::toArray(),
            'microsites' => GetMicrositesByStatusAndType::execute([
                'status' => Status::ACTIVE->value,
                'type' => MicrositesTypes::SUBSCRIPTIONS->value,
            ]),
            'subscription' => $this->model,
        ];
    }

}
