<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\Calendar;
use Illuminate\Http\JsonResponse;

class CalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $calendars = Calendar::all();

        return response()->json([
            'status' => 'Success',
            'data' => $calendars
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Calendar $calendar
     * @return JsonResponse
     */
    public function show(Calendar $calendar): JsonResponse
    {
        return response()->json($calendar);
    }
}
