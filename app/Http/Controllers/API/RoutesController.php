<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Routes;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
        return $this->getRoute($route);
    }

    /**
     * Get all information for one route from a specific time
     *
     * @param Request $request
     * @param Routes $route
     * @return JsonResponse
     */
    public function scheduleRoute(Request $request, Routes $route): JsonResponse
    {
        $request->validate([
            'time' => 'date|nullable',
        ]);

        return $this->getRoute($route, $request->time);
    }

    /**
     * @param Routes $route
     * @param $choosedTime
     * @return JsonResponse
     */
    public function getRoute(Routes $route, $choosedTime = null): JsonResponse
    {
        $actualTime = Carbon::now()->format('Y-m-d');

        if (isset($choosedTime)) {
            $timeNow = Carbon::parse($choosedTime)->format('H:m:s');
            $timePlus1Hour = Carbon::parse($choosedTime)->addHours(1)->format('H:m:s');
            $day = strtolower(Carbon::parse($choosedTime)->englishDayOfWeek);
            $date = Carbon::parse($choosedTime)->format('d/m');
        } else {
            $timeNow = Carbon::now()->format('H:m:s');
            $timePlus1Hour = Carbon::now()->addHours(1)->format('H:m:s');
            $day = strtolower(Carbon::now()->englishDayOfWeek);
            $date = 'auj';
        }

        $route->load([
            'trips',
            'trips.calendar' => function ($query) use ($day) {
                $query->where($day, '=', 1);
            },
            'trips.calendar.calendarDates',
            'trips.stopTimes' => function ($query) use ($timeNow, $timePlus1Hour) {
                $query
                    ->whereBetween('departure_time', [$timeNow, $timePlus1Hour]);
            },
            'trips.stopTimes.stop',
        ]);

        $trips = [];
        // Get datas without empty stops or calendar
        foreach ($route->trips as $key => $trip) {
            if (!$trip->stopTimes->isEmpty() && $trip->calendar != null) {
                $trips[] = $trip;
            }
        }
        // Filter trips with calendar dates exceptions (2 = removed trip)
        foreach ($trips as $key => $trip) {
            if (!$trip->calendar->calendarDates->isEmpty()) {
                foreach ($trip->calendar->calendarDates as $calendarDate) {
                    if ($calendarDate->date == $actualTime && $calendarDate->exception_type == 2) {
                        unset($trips[$key]);
                    }
                }
            }
        }

        unset($route['trips']);
        $route['trips'] = $trips;

        return response()->json([
            'time' => $choosedTime,
            'data' => $route,
            'date' => $date,
            'startTime' => Carbon::createFromFormat('H:m:s', $timeNow)->format('H'),
            'endTime' => Carbon::createFromFormat('H:m:s', $timePlus1Hour)->format('H'),
        ]);
    }
}
