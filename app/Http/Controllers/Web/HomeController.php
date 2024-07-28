<?php

namespace App\Http\Controllers\Web;

use App\Domain\Microsites\Models\Microsite;
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

    public function formMicrosite(Microsite $microsite): Response|RedirectResponse
    {
        $template = config('microsites.forms')[$microsite->microsites_type_id];
        return Inertia::render($template, new FormMicrosite($microsite));
    }
}
