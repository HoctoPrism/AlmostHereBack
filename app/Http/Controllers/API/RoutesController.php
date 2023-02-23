<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Routes;
use App\Models\Trips;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class RoutesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $routes = Routes::with(['agency'])->get();

        return response()->json([
            'status' => 'Success',
            'data' => $routes
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Routes $route
     * @return JsonResponse
     */
    public function show(Routes $route): JsonResponse
    {
        $route->load(['agency']);
        return response()->json($route);
    }

    /**
     * Get all information for one route
     *
     * @param Routes $route
     * @return JsonResponse
     */
    public function getOneRoute(Routes $route): JsonResponse
    {
        $timeNow = Carbon::now()->format('H:m:s');
        $timePlus1Hour = Carbon::now()->addHours(1)->format('H:m:s');
        $actualDay = Carbon::now()->englishDayOfWeek;

        $route->load([
            'trips',
            'trips.calendar'=> function ($query) use ($actualDay) {
                $query->where($actualDay, '=', 1);
            },
            /*'trips.calendar.calendarDates',*/
            'trips.stopTimes' => function ($query) use ($timeNow, $timePlus1Hour) {
                $query
                    ->whereBetween('departure_time', [$timeNow, $timePlus1Hour])
                ;
            },
            'trips.stopTimes.stop',
        ]);

        $trips = [];
        foreach ($route->trips as $key => $trip){
            if (!$trip->stopTimes->isEmpty()){
                $trips[] = $trip;
            }
        }
        unset($route['trips']);
        $route['trips'] = $trips;

        return response()->json([
            'data' => $route,
            'date' => $actualDay,
            'startTime' => Carbon::createFromFormat('H:m:s', $timeNow)->format('H'),
            'endTime' => Carbon::createFromFormat('H:m:s', $timePlus1Hour)->format('H'),
        ]);
    }
}
