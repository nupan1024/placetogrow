<?php

namespace App\Domain\Roles\ViewModels;

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
            'roles' => Role::select('id', 'name')->get(),
        ];
    }
}
