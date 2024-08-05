<?php

namespace App\Domain\Users\ViewModels;

use App\Domain\Users\Models\User;
use App\Support\Definitions\Status;
use App\Support\ViewModels\ViewModel;
use Spatie\Permission\Models\Role;

class CreateViewModel extends ViewModel
{
    public function __construct()
    {
        parent::__construct(new User());
    }

    public function toArray(): array
    {
        return [
            'roles' => Role::all(),
            'status' => Status::asOptions(),
        ];
    }
}
