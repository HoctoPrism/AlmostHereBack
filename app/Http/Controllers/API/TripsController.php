<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Trips;
use Illuminate\Http\Request;
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

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function tripInfoForBigMap(Request $request): JsonResponse
    {
        $request->validate(
            [
                'routes' => 'array|nullable',
            ]
        );

        $routes = $request->input('routes');
        $result = [];

        foreach ($routes as $route) {
            $result[] = Trips::where('route_id', $route)
                ->select('trip_id', 'shape_id', 'route_id')
                ->with([
                    'route' => function ($query) {
                        $query->select('route_id', 'route_color', 'route_text_color');
                    },
                    'stopTimes' => function ($query) {
                        $query->select('stop_id', 'trip_id');
                    },
                    'stopTimes.stop' => function ($query) {
                        $query->select('stop_id', 'stop_lat', 'stop_lon', 'stop_name');
                    },
                    'shape'
                ])->first()
            ;
        }

        return response()->json($result);
    }
}
