<?php

namespace App\Http\Controllers\Web\Admin;

use App\Domain\Settings\Actions\UpdateSetting;
use App\Domain\Settings\ViewModels\SettingViewModel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Setting\UpdateSettingRequest;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SettingController extends Controller
{
    public function index(): Response
    {
        return Inertia::render(
            'Admin/Settings/Index',
            app(SettingViewModel::class)
        );
    }

    public function update(UpdateSettingRequest $request): RedirectResponse
    {
        UpdateSetting::execute($request->validated());

        return redirect()->route('settings')->with([
            'message' => __('settings.success_update'),
            'type' => 'success',
        ]);
    }
}
