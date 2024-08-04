<?php

namespace App\Http\Controllers\Api;

use App\Domain\Invoices\Actions\ListInvoicesByUser;
use App\Domain\Users\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\StandardResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InvoicesController extends Controller
{
    public function list(Request $request, User $user): JsonResponse
    {
        $filter = $request->get('filter');

        return response()->json(
            new StandardResource(ListInvoicesByUser::execute([
                'filter' => $filter,
                'user_id' => $user->id,
                'page' => $request->get('page', 1),
            ]))
        );
    }
}
