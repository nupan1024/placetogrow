<?php

namespace App\Http\Controllers\Web\Admin;

use App\Domain\Imports\Actions\CreateImport;
use App\Domain\Invoices\Actions\CreateInvoice;
use App\Domain\Invoices\Actions\DeleteInvoice;
use App\Domain\Invoices\Actions\UpdateInvoice;
use App\Domain\Invoices\Models\Invoice;
use App\Domain\Invoices\ViewModels\CreateViewInvoices;
use App\Domain\Invoices\ViewModels\EditViewInvoices;
use App\Domain\Invoices\ViewModels\ImportViewInvoices;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Invoice\CreateInvoiceRequest;
use App\Http\Requests\Admin\Invoice\ImportInvoicesRequest;
use App\Http\Requests\Admin\Invoice\UpdateInvoiceRequest;
use App\Jobs\ProcessImportInvoices;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class InvoiceController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Invoices/List');
    }

    public function create(): Response
    {
        return Inertia::render(
            'Admin/Invoices/Create',
            app(CreateViewInvoices::class)
        );
    }

    public function store(CreateInvoiceRequest $request): RedirectResponse
    {
        CreateInvoice::execute($request->validated());

        return redirect()->route('invoices')->with([
            'message' => __('invoices.success_create'),
            'type' => 'success',
        ]);
    }

    public function edit(Invoice $invoice): Response
    {
        return Inertia::render(
            'Admin/Invoices/Edit',
            new EditViewInvoices($invoice)
        );
    }

    public function update(UpdateInvoiceRequest $request, Invoice $invoice): RedirectResponse
    {
        UpdateInvoice::execute($request->validated(), $invoice);

        return redirect()->route('invoices')->with([
            'message' => __('invoices.success_update'),
            'type' => 'success',
        ]);
    }

    public function delete(Invoice $invoice): RedirectResponse
    {
        if(!DeleteInvoice::execute([], $invoice)) {
            return redirect()->route('invoices')->with([
                'message' => __('invoices.error_delete'),
                'type' => 'error',
            ]);
        }

        return redirect()->route('invoices')->with([
            'message' => __('invoices.success_delete'),
            'type' => 'success',
        ]);
    }

    public function imports(): Response
    {
        return Inertia::render(
            'Admin/Invoices/Import/List',
            app(ImportViewInvoices::class)
        );
    }

    public function import(ImportInvoicesRequest $request): RedirectResponse
    {
        $import = CreateImport::execute($request->validated());
        dispatch(new ProcessImportInvoices($import, $request->get('microsite_id')));
        return redirect()->route('invoices.imports');
    }

}
