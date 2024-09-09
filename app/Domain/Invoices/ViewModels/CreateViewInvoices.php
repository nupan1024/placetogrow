<?php

namespace App\Domain\Invoices\ViewModels;

use App\Domain\Invoices\Models\Invoice;
use App\Domain\Microsites\Actions\GetMicrositesByStatusAndType;
use App\Domain\Users\Actions\GetUsersByRole;
use App\Support\Definitions\MicrositesTypes;
use App\Support\Definitions\Roles;
use App\Support\Definitions\Status;
use App\Support\ViewModels\ViewModel;

class CreateViewInvoices extends ViewModel
{
    public function __construct()
    {
        parent::__construct(new Invoice());
    }

    public function toArray(): array
    {
        return [
            'users' => GetUsersByRole::execute(['role' => Roles::GUEST->value]),
            'microsites' => GetMicrositesByStatusAndType::execute([
                'status' => Status::ACTIVE->value,
                'type' => MicrositesTypes::INVOICE->value,
            ]),
        ];
    }

}
