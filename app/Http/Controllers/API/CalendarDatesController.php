<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CalendarDates;
use Illuminate\Http\JsonResponse;

class CalendarDatesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $calendar_dates = CalendarDates::with(['service'])->get();

        return response()->json([
            'status' => 'Success',
            'data' => $calendar_dates
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param CalendarDates $calendardate
     * @return JsonResponse
     */
    public function show(CalendarDates $calendardate): JsonResponse
    {
        $calendardate->load(['service']);
        return response()->json($calendardate);
    }
}
