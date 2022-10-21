<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Shapes;
use Illuminate\Http\JsonResponse;

class ShapesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $shapes = Shapes::all();

        return response()->json([
            'status' => 'Success',
            'data' => $shapes
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Shapes $shape
     * @return JsonResponse
     */
    public function show(Shapes $shape): JsonResponse
    {
        return response()->json($shape);
    }
}
