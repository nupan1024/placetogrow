<?php

namespace App\Http\Controllers\Web\Admin;

use App\Domain\Roles\Actions\CreateRole;
use App\Domain\Roles\Actions\DeleteRole;
use App\Domain\Roles\Actions\UpdateRole;
use App\Domain\Roles\ViewModels\CreateViewModel;
use App\Domain\Roles\ViewModels\UpdateViewModel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Role\CreateRoleRequest;
use App\Http\Requests\Admin\Role\UpdateRoleRequest;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Roles/List');
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Roles/Create', new CreateViewModel());
    }

    public function store(CreateRoleRequest $request): RedirectResponse
    {
        CreateRole::execute($request->validated());

        return redirect()->route('roles')->with([
            'message' => __('roles.success_create'),
            'type' => 'success',
        ]);
    }

    public function edit(Role $role): Response
    {
        return Inertia::render('Admin/Roles/Edit', new UpdateViewModel($role));
    }

    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        UpdateRole::execute($request->validated(), $role);

        return redirect()->route('roles')->with([
            'message' => __('roles.success_update'),
            'type' => 'success',
        ]);
    }

    public function delete(Role $role): RedirectResponse
    {
        if (!DeleteRole::execute([], $role)) {
            return redirect()->route('roles')->with([
                'message' => trans('roles.error_delete'),
                'type' => 'error',
            ]);
        }

        return redirect()->route('roles')->with([
            'message' => trans('roles.success_delete'),
            'type' => 'success',
        ]);
    }

}
