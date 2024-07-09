<?php

namespace App\Http\Controllers\Web\Admin;

use App\Domain\Microsites\Actions\CreateMicrosite;
use App\Domain\Microsites\Actions\DeleteMicrosite;
use App\Domain\Microsites\Actions\UpdateMicrosite;
use App\Domain\Microsites\ViewModels\CreateViewModel;
use App\Domain\Microsites\ViewModels\EditViewModel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Microsite\CreateMicrositeRequest;
use App\Http\Requests\Admin\Microsite\UpdateMicrositeRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Inertia\Response;

class MicrositeController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Microsites/List');
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Microsites/Create', new CreateViewModel());
    }

    public function store(CreateMicrositeRequest $request): RedirectResponse
    {
        CreateMicrosite::execute($request->validated());
        Cache::forget('admin_microsites_page_1_filter_ ');
        Cache::forget('microsites_page_1_filter_ ');

        return redirect()->route('microsites')->with([
            'message' => 'Se ha creado el micrositio con éxito.',
            'type' => 'success',
        ]);
    }

    public function edit(int $id): Response
    {
        return Inertia::render('Admin/Microsites/Edit', new EditViewModel($id));
    }

    public function update(UpdateMicrositeRequest $request, int $id): RedirectResponse
    {
        UpdateMicrosite::execute(['fields' => $request->validated(), 'id' => $id]);

        return redirect()->route('microsites')->with([
            'message' => 'Se actualizó el micrositio con éxito.',
            'type' => 'success',
        ]);
    }

    public function delete(int $id): RedirectResponse
    {
        DeleteMicrosite::execute(['id' => $id]);

        return redirect()->route('microsites');
    }
}
