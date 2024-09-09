<?php

namespace App\Http\Controllers\Api\Admin;

use App\Domain\Imports\Actions\ListImport;
use App\Domain\Invoices\Actions\ListInvoices;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\StandardResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InvoicesController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $filter = $request->get('filter');

        return response()->json(
            new StandardResource(ListInvoices::execute([
                'filter' => $filter,
                'page' => $request->get('page', 1),
            ]))
        );
    }
    public function imports(Request $request): JsonResponse
    {
        $filter = $request->get('filter');

        return response()->json(
            new StandardResource(ListImport::execute([
                'filter' => $filter,
                'page' => $request->get('page', 1),
            ]))
        );
    }
}
