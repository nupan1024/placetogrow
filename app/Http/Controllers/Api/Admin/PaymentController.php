<?php

namespace App\Http\Controllers\Api\Admin;

use App\Domain\Payments\Actions\ListPayments;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\StandardResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function list(Request $request): JsonResponse
    {
        $filter = $request->get('filter');

        return response()->json(
            new StandardResource(ListPayments::execute([
                'filter' => $filter,
                'page' => $request->get('page', 1),
            ]))
        );
    }
}
