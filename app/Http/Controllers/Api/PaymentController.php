<?php

namespace App\Http\Controllers\Api;

use App\Domain\Payments\Actions\ListPaymentsByUser;
use App\Domain\Users\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\StandardResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request, User $user): JsonResponse
    {
        $filter = $request->get('filter');

        return response()->json(
            new StandardResource(ListPaymentsByUser::execute([
                'filter' => $filter,
                'user_id' => $user->id,
                'page' => $request->get('page', 1),
            ]))
        );
    }
}
