<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Routes;
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
}