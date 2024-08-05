<?php

namespace App\Domain\Invoices\ViewModels;

use App\Domain\Microsites\Actions\GetMicrositesByStatusAndType;
use App\Domain\Users\Actions\GetUsersByRole;
use App\Support\Definitions\MicrositesTypes;
use App\Support\Definitions\Roles;
use App\Support\Definitions\Status;
use App\Support\ViewModels\ViewModel;

class EditViewInvoices extends ViewModel
{
    public function toArray(): array
    {
        return [
            'users' => GetUsersByRole::execute(['role' => Roles::GUEST->value]),
            'invoice' => $this->model(),
            'microsites' => GetMicrositesByStatusAndType::execute([
                'status' => Status::ACTIVE->value,
                'type' => MicrositesTypes::INVOICE->value,
            ]),
        ];
    }
}
