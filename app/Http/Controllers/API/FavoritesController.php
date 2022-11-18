<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Favorites;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class FavoritesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $favorites = Favorites::with(['route', 'user'])->get();

        return response()->json([
            'status' => 'Success',
            'data' => $favorites
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $this->validate($request ,[
            'name' => 'required|string|max:50',
            'route_id' => 'required|integer',
            'user_id' => 'required|integer',
        ]);

        $favorite = Favorites::create([
            'name' => $request->name,
            'route_id' => $request->route_id,
            'user_id' => $request->user_id,
        ]);

        return response()->json([
            'status' => 'Success',
            'data' => $favorite,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Favorites $favorite
     * @return JsonResponse
     */
    public function show(Favorites $favorite): JsonResponse
    {
        $favorite->load(['route', 'user']);
        return response()->json($favorite);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Favorites $favorite
     * @return JsonResponse
     */
    public function destroy(Favorites $favorite): JsonResponse
    {
        $favorite->delete();

        return response()->json([
            'status' => 'Supprimer avec success'
        ]);
    }
}
