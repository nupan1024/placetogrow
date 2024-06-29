<?php

namespace App\Http\Controllers\Api\Admin;

use App\Domain\Microsites\Models\Microsite;
use App\Domain\Users\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\StandardResource;
use App\Support\Definitions\Status;
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

    public function list(Request $request): JsonResponse
    {
        $filter = $request->get('filter');

        $microsites = Microsite::select(
            'microsites.id',
            'microsites.name',
            'categories.name as category',
            'microsites_types.name as type',
            'microsites.status',
        )
            ->where('microsites.status', Status::ACTIVE->value)
            ->join('categories', 'microsites.category_id', '=', 'categories.id')
            ->join('microsites_types', 'microsites.microsites_type_id', '=', 'microsites_types.id')
            ->when($filter, function ($query, $filter) {
                return $query->where(function ($query) use ($filter) {
                    $query->where('microsites.name', 'like', '%'.$filter.'%')
                        ->orWhere('categories.name', 'like', '%'.$filter.'%')
                        ->orWhere('microsites_types.name', 'like', '%'.$filter.'%');
                });
            })->latest('microsites.id')->paginate(10);

        return response()->json(new StandardResource($microsites));
    }
}
