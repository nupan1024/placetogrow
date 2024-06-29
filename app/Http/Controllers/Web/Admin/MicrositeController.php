<?php

namespace App\Http\Controllers\Web\Admin;

use App\Domain\Microsites\Actions\CreateMicrosite;
use App\Domain\Microsites\Actions\DeleteMicrosite;
use App\Domain\Microsites\ViewModels\CreateViewModel;
use App\Domain\Microsites\ViewModels\EditViewModel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Microsite\CreateMicrositeRequest;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class MicrositeController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Microsites/List');
    }

    public function viewCreate(): Response
    {
        return Inertia::render('Admin/Microsites/Create', new CreateViewModel());
    }

    public function create(CreateMicrositeRequest $request): RedirectResponse
    {
        CreateMicrosite::execute($request->validated());
        session()->flash('success', __('products.success_create'));

        return redirect()->route('microsites');
    }

    public function viewUpdate(string $id): Response
    {
        return Inertia::render('Admin/Microsites/Edit', new EditViewModel($id));
    }

    public function update(CreateMicrositeRequest $request): RedirectResponse
    {
        CreateMicrosite::execute($request->validated());
        session()->flash('success', __('products.success_create'));

        return redirect()->route('microsites');
    }

    public function delete(string $id): RedirectResponse
    {
        if (! $id || ! is_numeric($id)) {
            return redirect()->route('microsites');
        }

        DeleteMicrosite::execute(['id' => $id]);

        return redirect()->route('microsites');
    }
}
