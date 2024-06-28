<?php

namespace App\Http\Controllers\Api\Admin;

use App\Domain\Microsites\Models\Microsite;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\StandardResource;
use App\Support\Definitions\Status;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MicrositeController extends Controller
{
    public function list(Request $request): JsonResponse {
        $filter = $request->get('filter');

        $microsites = Microsite::select(
            'microsites.id',
            'microsites.name',
            'categories.name as category',
            'microsites_types.name as type',
            'microsites.status',
        )
            ->where('microsites.status', Status::ACTIVE->value)
            ->join('categories','microsites.category_id', '=', 'categories.id')
            ->join('microsites_types','microsites.microsites_type_id', '=', 'microsites_types.id')
            ->when($filter, function($query, $filter){
                return $query->where(function($query) use ($filter) {
                    $query->where('microsites.name', 'like',  '%' . $filter . '%')
                        ->orWhere('categories.name', 'like',  '%' . $filter . '%')
                        ->orWhere('microsites_types.name', 'like',  '%' . $filter . '%');
                });
            })->latest('microsites.id')->paginate(10);

        return response()->json(new StandardResource($microsites));
    }
}
