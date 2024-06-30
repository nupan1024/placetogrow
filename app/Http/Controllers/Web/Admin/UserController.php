<?php

namespace App\Http\Controllers\Web\Admin;

use App\Domain\Users\Actions\CreateUser;
use App\Domain\Users\ViewModels\CreateViewModel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\CreateUserRequest;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Users/List');
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Users/Create', new CreateViewModel());
    }

    public function store(CreateUserRequest $request): RedirectResponse
    {
        CreateUser::execute($request->validated());

        return redirect()->route('users')->with([
            'message' => 'Se creó el usuario con éxito.',
            'type' => 'success',
        ]);
    }
}
