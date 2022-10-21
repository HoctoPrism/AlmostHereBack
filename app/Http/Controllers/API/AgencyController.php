<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use Illuminate\Http\JsonResponse;

class AgencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $agencies = Agency::all();

        return response()->json([
            'status' => 'Success',
            'data' => $agencies
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Agency $agency
     * @return JsonResponse
     */
    public function show(Agency $agency): JsonResponse
    {
        return response()->json($agency);
    }
}
