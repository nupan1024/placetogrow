<?php

namespace App\Domain\Users\ViewModels;

use App\Domain\Users\Models\Role;
use App\Domain\Users\Models\User;
use App\Support\Definitions\Status;
use App\Support\ViewModels\ViewModel;

class EditViewModel extends ViewModel
{
    public function __construct($id)
    {
        $user = User::find($id);
        parent::__construct($user ?: new User());
    }

    public function toArray(): array
    {
        return [
            'user' => $this->model()->getRawOriginal(),
            'roles' => Role::all(),
            'status' => Status::asOptions(),
        ];
    }
}
