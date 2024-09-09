<?php

namespace App\Http\Controllers\Api\Admin;

use App\Domain\Invoices\Actions\ListInvoicesByUser;
use App\Domain\Payments\Actions\ListPaymentsByUser;
use App\Domain\SubscriptionUser\Actions\ListSubscriptionUser;
use App\Domain\Users\Actions\ListUsers;
use App\Domain\Users\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\StandardResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $filter = $request->get('filter');

        return response()->json(
            new StandardResource(ListUsers::execute([
                'filter' => $filter,
                'page' => $request->get('page', 1),
            ]))
        );
    }
    public function invoices(Request $request, User $user): JsonResponse
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

    public function payments(Request $request, User $user): JsonResponse
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

    public function subscriptions(Request $request, User $user): JsonResponse
    {
        $filter = $request->get('filter');

        return response()->json(
            new StandardResource(ListSubscriptionUser::execute([
                'filter' => $filter,
                'user_id' => $user->id,
                'page' => $request->get('page', 1),
            ]))
        );
    }
}
