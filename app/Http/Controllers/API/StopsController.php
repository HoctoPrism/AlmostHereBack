<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Stops;
use Illuminate\Http\JsonResponse;

class StopsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $stops = Stops::with(['parentStation'])->get();

        return response()->json([
            'status' => 'Success',
            'data' => $stops
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Stops $stop
     * @return JsonResponse
     */
    public function show(Stops $stop): JsonResponse
    {
        $stop->load(['parentStation']);
        return response()->json($stop);
    }
}
