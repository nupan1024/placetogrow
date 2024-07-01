<?php

namespace App\Http\Controllers\Web\Admin;

use App\Domain\Roles\ViewModels\ListViewModel;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class RolesController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Roles/List', new ListViewModel());
    }
}
