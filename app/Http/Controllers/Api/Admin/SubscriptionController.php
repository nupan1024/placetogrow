<?php

namespace App\Http\Controllers\Api\Admin;

use App\Domain\Subscriptions\Actions\ListSubscription;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\StandardResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $filter = $request->get('filter');

        return response()->json(
            new StandardResource(ListSubscription::execute([
                'filter' => $filter,
                'page' => $request->get('page', 1),
            ]))
        );
    }
}
