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
use App\Support\Definitions\MicrositesTypes;
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
        if ($request->microsites_type_id == MicrositesTypes::INVOICE->value) {
            if (! $this->validateDateExpirePay($request->date_expire_pay)) {
                return redirect()->route('microsites')->with([
                    'message' => 'El campo fecha limite de pago es obligatorio y debe ser mayor a la fecha actual.',
                    'type' => 'error',
                ]);
            }
        }

        CreateMicrosite::execute($request->validated());

        return redirect()->route('microsites')->with([
            'message' => 'Se ha creado el micrositio con éxito.',
            'type' => 'success',
        ]);
    }

    public function viewUpdate(int $id): Response
    {
        return Inertia::render('Admin/Microsites/Edit', new EditViewModel($id));
    }

    public function update(UpdateMicrositeRequest $request, int $id): RedirectResponse
    {
        if (! $id || ! is_numeric($id)) {
            return redirect()->route('microsites')->with([
                'message' => 'Hubo un error al actualizar el micrositio, por favor intenta de nuevo.',
                'type' => 'error',
            ]);
        }

        if ($request->microsites_type_id == MicrositesTypes::INVOICE->value) {
            if (! $this->validateDateExpirePay($request->date_expire_pay)) {
                return redirect()->route('microsites')->with([
                    'message' => 'El campo fecha limite de pago es obligatorio y debe ser mayor a la fecha actual.',
                    'type' => 'error',
                ]);
            }
        }

        UpdateMicrosite::execute(['fields' => $request->validated(), 'id' => $id]);

        return redirect()->route('microsites')->with([
            'message' => 'Se actualizó el micrositio con éxito.',
            'type' => 'success',
        ]);
    }

    public function delete(int $id): RedirectResponse
    {
        if (! $id || ! is_numeric($id)) {
            return redirect()->route('microsites')->with([
                'message' => 'Hubo un error al eliminar el micrositio, por favor intenta de nuevo.',
                'type' => 'error',
            ]);
        }

        DeleteMicrosite::execute(['id' => $id]);

        return redirect()->route('microsites');
    }

    public function validateDateExpirePay($date_expire_pay): bool
    {
        if (! $date_expire_pay || $date_expire_pay < date('Y-m-d')) {
            return false;
        }

        return true;
    }
}
