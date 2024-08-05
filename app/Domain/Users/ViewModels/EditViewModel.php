<?php

namespace App\Domain\Users\ViewModels;

use App\Support\Definitions\Status;
use App\Support\ViewModels\ViewModel;
use Spatie\Permission\Models\Role;

class EditViewModel extends ViewModel
{
    public function toArray(): array
    {
        return [
            'user' => $this->model()->getRawOriginal(),
            'roles' => Role::all(),
            'status' => Status::asOptions(),
        ];
    }
}
