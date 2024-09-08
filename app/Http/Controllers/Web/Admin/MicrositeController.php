<?php

namespace App\Http\Controllers\Web\Admin;

use App\Domain\Microsites\Actions\CreateMicrosite;
use App\Domain\Microsites\Actions\DeleteMicrosite;
use App\Domain\Microsites\Actions\UpdateMicrosite;
use App\Domain\Microsites\Models\Microsite;
use App\Domain\Microsites\ViewModels\CreateViewModel;
use App\Domain\Microsites\ViewModels\EditViewModel;
use App\Domain\Microsites\ViewModels\ListByMicrositeViewModel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Microsite\CreateMicrositeRequest;
use App\Http\Requests\Admin\Microsite\UpdateMicrositeRequest;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class MicrositeController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Microsites/List');
    }

    public function invoices(Microsite $microsite): Response
    {
        return Inertia::render(
            'Admin/Microsites/ListInvoice',
            new ListByMicrositeViewModel($microsite)
        );
    }

    public function subscriptions(Microsite $microsite): Response
    {
        return Inertia::render(
            'Admin/Microsites/ListSubscription',
            new ListByMicrositeViewModel($microsite)
        );
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Microsites/Create', new CreateViewModel());
    }

    public function store(CreateMicrositeRequest $request): RedirectResponse
    {
        CreateMicrosite::execute($request->validated());

        return redirect()->route('microsites')->with([
            'message' => __('microsites.success_create'),
            'type' => 'success',
        ]);
    }

    public function edit(Microsite $microsite): Response
    {
        return Inertia::render('Admin/Microsites/Edit', new EditViewModel($microsite));
    }

    public function update(UpdateMicrositeRequest $request, Microsite $microsite): RedirectResponse
    {
        UpdateMicrosite::execute($request->validated(), $microsite);

        return redirect()->route('microsites')->with([
            'message' => __('microsites.success_update'),
            'type' => 'success',
        ]);
    }

    public function delete(Microsite $microsite): RedirectResponse
    {
        $response = DeleteMicrosite::execute([], $microsite);
        if($response['status'] === false) {
            return redirect()->route('microsites')->with([
                'message' => $response['message'],
                'type' => 'error',
            ]);
        }

        return redirect()->route('microsites');
    }
}
