<?php

namespace App\Http\Controllers\Web\Admin;

use App\Domain\Settings\ViewModels\SettingViewModel;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class SettingController extends Controller
{
    public function index(): Response
    {
        return Inertia::render(
            'Admin/Settings/Index',
            app(SettingViewModel::class)
        );
    }
}
