<?php

namespace App\Domain\Roles\ViewModels;

use App\Support\ViewModels\ViewModel;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateViewModel extends ViewModel
{
    public function __construct()
    {
        parent::__construct(new Role());
    }
    public function toArray(): array
    {
        return [
            'permissions' => Permission::all(),
        ];
    }
}
