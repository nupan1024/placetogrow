<?php

namespace App\Http\Controllers\Web\Admin;

use App\Domain\Microsites\ViewModels\CreateViewModel;
use App\Domain\Microsites\ViewModels\EditViewModel;
use App\Domain\Microsites\ViewModels\ListViewModel;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class MicrositesController extends Controller
{
    public function index(): Response {
        return Inertia::render('Admin/Microsites/List', new ListViewModel());
    }
    public function create(): Response {
        return Inertia::render('Admin/Microsites/Create', new CreateViewModel());
    }

    public function edit($id): Response {
        return Inertia::render('Admin/Microsites/Edit', new EditViewModel($id));
    }
}
