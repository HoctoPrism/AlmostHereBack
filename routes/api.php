<?php

use App\Http\Controllers\API\AgencyController;
use App\Http\Controllers\API\CalendarController;
use App\Http\Controllers\API\CalendarDatesController;
use App\Http\Controllers\API\FavoritesController;
use App\Http\Controllers\API\FrequenciesController;
use App\Http\Controllers\API\MessagesController;
use App\Http\Controllers\API\RoutesController;
use App\Http\Controllers\API\ShapesController;
use App\Http\Controllers\API\StopsController;
use App\Http\Controllers\API\StopTimesController;
use App\Http\Controllers\API\TripsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(FavoritesController::class)->group(function () {
    Route::get('favorites', 'index');
    Route::get('favorites/{favorite}', 'show');
    Route::post('favorites', 'store');
    Route::patch('favorites/{favorite}', 'update');
    Route::delete('favorites/{favorite}', 'destroy');
});

Route::controller(MessagesController::class)->group(function () {
    Route::get('messages', 'index');
    Route::get('messages/{message}', 'show');
    Route::post('messages', 'store');
    Route::patch('messages/{message}', 'update');
    Route::delete('messages/{message}', 'destroy');
});

Route::apiResource("agencies", AgencyController::class);
Route::apiResource("calendars", CalendarController::class);
Route::apiResource("calendardates", CalendarDatesController::class);
Route::apiResource("frequencies", FrequenciesController::class);
Route::apiResource("routes", RoutesController::class);
Route::apiResource("shapes", ShapesController::class);
Route::apiResource("stops", StopsController::class);
Route::apiResource("stoptimes", StopTimesController::class);
Route::apiResource("trips", TripsController::class);
