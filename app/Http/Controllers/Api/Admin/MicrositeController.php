<?php

namespace App\Http\Controllers\Api\Admin;

use App\Domain\Microsites\Actions\ListInvoicesByMicrosite;
use App\Domain\Microsites\Actions\ListMicrositesForAdmin;
use App\Domain\Microsites\Actions\ListSubscriptionsByMicrosite;
use App\Domain\Microsites\Models\Microsite;
use App\Domain\Users\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\StandardResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class MicrositeController extends Controller
{
    public function getToken(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $user = User::where('email', $request->email)->first();

            if (! $user || ! Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }

            return response()->json(new StandardResource(['token' => $user->createToken('Token')->plainTextToken]));
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    public function index(Request $request): JsonResponse
    {
        $filter = $request->get('filter');

        return response()->json(
            new StandardResource(ListMicrositesForAdmin::execute([
                'filter' => $filter,
                'page' => $request->get('page', 1),
            ]))
        );
    }
    public function subscriptions(Request $request, Microsite $microsite): JsonResponse
    {
        $filter = $request->get('filter');

        return response()->json(
            new StandardResource(ListSubscriptionsByMicrosite::execute([
                'filter' => $filter,
                'page' => $request->get('page', 1),
            ], $microsite))
        );
    }
    public function invoices(Request $request, Microsite $microsite): JsonResponse
    {
        $filter = $request->get('filter');

        return response()->json(
            new StandardResource(ListInvoicesByMicrosite::execute([
                'filter' => $filter,
                'page' => $request->get('page', 1),
            ], $microsite))
        );
    }
}
