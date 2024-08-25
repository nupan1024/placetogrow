<?php

namespace App\Domain\Roles\ViewModels;

use App\Support\ViewModels\ViewModel;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * @extends ViewModel<Role>
 */
class UpdateViewModel extends ViewModel
{
    public function toArray(): array
    {
        return [
            'all_permissions' => Permission::all(),
            'role' => $this->model(),
            'permissions' => $this->model()->permissions()->pluck('name'),
        ];
    }
}
