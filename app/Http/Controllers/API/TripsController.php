<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Trips;
use Illuminate\Http\JsonResponse;

class TripsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $trips = Trips::with(['shape', 'route', 'calendar'])->get();

        return response()->json([
            'status' => 'Success',
            'data' => $trips
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Trips $trip
     * @return JsonResponse
     */
    public function show(Trips $trip): JsonResponse
    {
        $trip->load(['shape', 'route', 'calendar']);
        return response()->json($trip);
    }

    /**
     * Display the specified resource.
     *
     * @param Trips $trip
     * @return JsonResponse
     */
    public function tripInfoMap(Trips $trip): JsonResponse
    {
        $trip->load(['shape', 'route', 'stopTimes', 'stopTimes.stop']);
        return response()->json($trip);
    }
}
