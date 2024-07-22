<?php

namespace App\Domain\Roles\ViewModels;

use App\Support\Definitions\Roles;
use App\Support\ViewModels\ViewModel;
use Spatie\Permission\Models\Role;

class ListViewModel extends ViewModel
{
    public function __construct()
    {
        parent::__construct(new Role());
    }

    public function toArray(): array
    {
        return [
            'roles' => Role::select('id', 'name')
                ->where('id', '!=', Roles::SUPER_ADMIN->value)
                ->get(),
        ];
    }
}
