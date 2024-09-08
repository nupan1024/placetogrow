<?php

namespace App\Domain\Invoices\ViewModels;

use App\Domain\Fields\Models\Field;
use App\Domain\Microsites\Actions\GetMicrositesByStatusAndType;
use App\Support\Definitions\MicrositesTypes;
use App\Support\Definitions\Status;
use App\Support\ViewModels\ViewModel;

class ImportViewInvoices extends ViewModel
{
    public function __construct()
    {
        parent::__construct(new Field());
    }

    public function toArray(): array
    {
        return [
            'microsites' => GetMicrositesByStatusAndType::execute([
                'status' => Status::ACTIVE->value,
                'type' => MicrositesTypes::INVOICE->value,
            ]),
        ];
    }

}
