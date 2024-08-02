<?php

namespace App\Http\Controllers\Web\Admin;

use App\Domain\Fields\Actions\CreateField;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Field\CreateFieldRequest;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class FieldsController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Fields/List');
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Fields/Create');
    }

    public function store(CreateFieldRequest $request): RedirectResponse
    {
        CreateField::execute($request->validated());

        return redirect()->route('fields')->with([
            'message' => __('microsites.success_create'),
            'type' => 'success',
        ]);
    }
}
