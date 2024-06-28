<?php

namespace App\Http\Controllers\Web\Admin;

use App\Domain\Microsites\Actions\StoreMicrosite;
use App\Domain\Microsites\ViewModels\CreateViewModel;
use App\Domain\Microsites\ViewModels\EditViewModel;
use App\Domain\Microsites\ViewModels\ListViewModel;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateMicrositeRequest;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class MicrositeController extends Controller
{
    public function index(): Response {
        return Inertia::render('Admin/Microsites/List', new ListViewModel());
    }
    public function create(): Response {
        return Inertia::render('Admin/Microsites/Create', new CreateViewModel());
    }

    public function store(CreateMicrositeRequest $request): RedirectResponse {
        StoreMicrosite::execute($request->validated());
        session()->flash('success', __('products.success_create'));

        return redirect()->route('microsites');
    }

    public function edit(string $id): Response {
        return Inertia::render('Admin/Microsites/Edit', new EditViewModel($id));
    }
}
