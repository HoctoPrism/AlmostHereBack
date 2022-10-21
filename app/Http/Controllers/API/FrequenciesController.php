<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Frequencies;
use Illuminate\Http\JsonResponse;

class FrequenciesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $frequencies = Frequencies::with(['trip'])->get();

        return response()->json([
            'status' => 'Success',
            'data' => $frequencies
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Frequencies $frequency
     * @return JsonResponse
     */
    public function show(Frequencies $frequency): JsonResponse
    {
        $frequency->load(['trip']);
        return response()->json($frequency);
    }
}
