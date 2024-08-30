<?php

namespace App\Http\Controllers\Api\Admin;

use App\Domain\Fields\Actions\ListFields;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\StandardResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FieldsController extends Controller
{
    public function list(Request $request): JsonResponse
    {
        $filter = $request->get('filter');
        $params = [
            'filter' => $filter,
            'page' => $request->get('page', 1),
        ];
        return response()->json(
            new StandardResource(ListFields::execute($params))
        );
    }
}
