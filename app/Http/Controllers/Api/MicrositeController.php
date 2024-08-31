<?php

namespace App\Http\Controllers\Api;

use App\Domain\Microsites\Actions\ListMicrositesForGuest;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\StandardResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MicrositeController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $filter = $request->get('filter');

        return response()->json(
            new StandardResource(ListMicrositesForGuest::execute([
                'filter' => $filter,
                'page' => $request->get('page', 1),
            ]))
        );
    }
}
