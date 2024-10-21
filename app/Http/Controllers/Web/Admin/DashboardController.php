<?php

namespace App\Http\Controllers\Web\Admin;

use App\Domain\Dashboard\ViewModels\ViewDashboard;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        return Inertia::render(
            'Admin/Dashboard',
            app(ViewDashboard::class)
        );
    }
}
