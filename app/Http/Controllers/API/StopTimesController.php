<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Calendar;
use App\Models\Routes;
use App\Models\Shapes;
use App\Models\StopTimes;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class StopTimesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {

        $stop_times = StopTimes::with(['stop', 'trip'])
            ->limit($request->query('limit'))
            ->offset($request->query('offset'))
            ->get()
        ;

        $stop_times->map(function ($st){
            $st['trip']->shape_id = Shapes::find($st['trip']->shape_id)->first();
            $st['trip']->route_id = Routes::find($st['trip']->route_id)->first();
            $st['trip']->service_id = Calendar::find($st['trip']->service_id)->first();
        });

        return response()->json([
            'status' => 'Success',
            'data' => $stop_times
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param StopTimes $stoptime
     * @return JsonResponse
     */
    public function show(StopTimes $stoptime): JsonResponse
    {
        $stoptime->load(['stop', 'trip']);

        $stoptime['trip']->shape_id = Shapes::find($stoptime['trip']->shape_id)->first();
        $stoptime['trip']->route_id = Routes::find($stoptime['trip']->route_id)->first();
        $stoptime['trip']->service_id = Calendar::find($stoptime['trip']->service_id)->first();

        return response()->json($stoptime);
    }
}
