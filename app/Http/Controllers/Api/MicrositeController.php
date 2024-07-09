<?php

namespace App\Http\Controllers\Api;

use App\Domain\Microsites\Actions\CreateMicrosite;
use App\Domain\Microsites\Actions\ListMicrositesForGuest;
use App\Domain\Microsites\ViewModels\CreateViewModel;
use App\Domain\Microsites\ViewModels\EditViewModel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Microsite\CreateMicrositeRequest;
use App\Http\Resources\Api\StandardResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MicrositeController extends Controller
{
    public function list(Request $request): JsonResponse
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
