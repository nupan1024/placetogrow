<?php

namespace App\Http\Controllers\Web;

use App\Domain\Microsites\Actions\GetMicrositeType;
use App\Domain\Microsites\ViewModels\FormMicrosite;
use App\Http\Controllers\Controller;
use App\Support\ViewModels\HomeViewModel;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Welcome', new HomeViewModel());
    }

    public function formMicrosite(int $id): Response|RedirectResponse
    {
        $microsite = GetMicrositeType::execute(['id' => $id]);

        if (! $microsite) {
            return redirect()->route('home');
        }

        $template = config('microsites.forms')[$microsite->microsites_type_id];

        return Inertia::render($template, new FormMicrosite($id));
    }
}
