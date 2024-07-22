<?php

namespace App\Domain\Roles\ViewModels;

use App\Domain\Users\Models\Role;
use App\Support\ViewModels\ViewModel;

class ListViewModel extends ViewModel
{
    public function __construct()
    {
        parent::__construct(new Role());
    }

    public function toArray(): array
    {
        return [
            'roles' => Role::select('id', 'name')->get(),
        ];
    }
}
