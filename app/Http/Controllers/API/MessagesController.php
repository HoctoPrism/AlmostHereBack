<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Messages;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class MessagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $messages = Messages::with(['favorite'])->get();

        return response()->json([
            'status' => 'Success',
            'data' => $messages
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
        $request->validate(
            [
                'message' => 'required|string|max:100',
                'favorite_id' => 'required|integer',
            ]
        );

        $message = Messages::create([
            'message' => $request->message,
            'favorite_id' => $request->favorite_id
        ]);

        return response()->json([
            'status' => 'Success',
            'data' => $message,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Messages $message
     * @return JsonResponse
     */
    public function show(Messages $message): JsonResponse
    {
        $message->load(['favorite']);
        return response()->json($message);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Messages $message
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update(Request $request, Messages $message): JsonResponse
    {
        $this->validate($request ,[
            'message' => 'required|string|max:100',
            'favorite_id' => 'required|integer',
        ]);

        $message->update([
            'message' => $request->message,
            'favorite_id' => $request->favorite_id
        ]);

        return response()->json([
            'status' => 'Mise à jour avec success',
            'date' => $message
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Messages $message
     * @return JsonResponse
     */
    public function destroy(Messages $message): JsonResponse
    {
        $message->delete();

        return response()->json([
            'status' => 'Supprimer avec success'
        ]);
    }
}
