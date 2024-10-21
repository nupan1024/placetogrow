<?php

namespace App\Http\Controllers\Web\Admin;

use App\Domain\Fields\Actions\CreateField;
use App\Domain\Fields\Actions\DeleteField;
use App\Domain\Fields\Actions\UpdateField;
use App\Domain\Fields\Models\Field;
use App\Domain\Fields\ViewModels\CreateViewFields;
use App\Domain\Fields\ViewModels\EditViewFields;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Field\CreateFieldRequest;
use App\Http\Requests\Admin\Field\UpdateFieldRequest;
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
        return Inertia::render(
            'Admin/Fields/Create',
            app(CreateViewFields::class)
        );
    }

    public function store(CreateFieldRequest $request): RedirectResponse
    {
        CreateField::execute($request->validated());

        return redirect()->route('fields')->with([
            'message' => __('fields.success_create'),
            'type' => 'success',
        ]);
    }

    public function edit(Field $field): Response
    {
        return Inertia::render(
            'Admin/Fields/Edit',
            new EditViewFields($field)
        );
    }

    public function update(UpdateFieldRequest $request, Field $field): RedirectResponse
    {
        UpdateField::execute($request->validated(), $field);

        return redirect()->route('fields')->with([
            'message' => __('fields.success_update'),
            'type' => 'success',
        ]);
    }

    public function delete(Field $field): RedirectResponse
    {
        if (!DeleteField::execute([], $field)) {
            return redirect()->route('fields')->with([
                'message' => __('fields.error_delete'),
                'type' => 'error',
            ]);
        }

        return redirect()->route('fields')->with([
            'message' => __('fields.success_delete'),
            'type' => 'success',
        ]);
    }

}
