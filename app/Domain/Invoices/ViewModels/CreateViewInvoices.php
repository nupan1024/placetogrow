<?php

namespace App\Domain\Invoices\ViewModels;

use App\Domain\Fields\Models\Field;
use App\Domain\Microsites\Actions\GetMicrositesByStatus;
use App\Domain\Users\Actions\GetUsersByRole;
use App\Support\Definitions\Roles;
use App\Support\Definitions\Status;
use App\Support\ViewModels\ViewModel;

class CreateViewInvoices extends ViewModel
{
    public function __construct()
    {
        parent::__construct(new Field());
    }
    public function toArray(): array
    {
        return [
            'users' => GetUsersByRole::execute(['role' => Roles::GUEST->value]),
            'microsites' => GetMicrositesByStatus::execute(['status' => Status::ACTIVE->value]),
        ];
    }
}
