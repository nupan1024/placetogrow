<?php

namespace App\Http\Controllers\Web\Admin;

use App\Domain\Microsites\Actions\CreateMicrosite;
use App\Domain\Microsites\Actions\DeleteMicrosite;
use App\Domain\Microsites\Actions\UpdateMicrosite;
use App\Domain\Microsites\Models\Microsite;
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
        Cache::forget(config('cache.stores.key.microsites_admin'));
        Cache::forget(config('cache.stores.key.microsites'));

        return redirect()->route('microsites')->with([
            'message' => 'Se ha creado el micrositio con éxito.',
            'type' => 'success',
        ]);
    }

    public function edit(Microsite $microsite): Response
    {
        return Inertia::render('Admin/Microsites/Edit', new EditViewModel($microsite));
    }

    public function update(UpdateMicrositeRequest $request, Microsite $microsite): RedirectResponse
    {
        UpdateMicrosite::execute(['fields' => $request->validated(), 'microsite' => $microsite]);
        Cache::forget(config('cache.stores.key.microsites_admin'));
        Cache::forget(config('cache.stores.key.microsites'));

        return redirect()->route('microsites')->with([
            'message' => 'Se actualizó el micrositio con éxito.',
            'type' => 'success',
        ]);
    }

    public function delete(Microsite $microsite): RedirectResponse
    {
        DeleteMicrosite::execute(['microsite' => $microsite]);
        Cache::forget(config('cache.stores.key.microsites_admin'));
        Cache::forget(config('cache.stores.key.microsites'));

        return redirect()->route('microsites');
    }
}
