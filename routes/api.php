<?php

use App\Http\Controllers\API\AgencyController;
use App\Http\Controllers\API\AuthController;
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
use App\Http\Controllers\API\UserController;
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

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
    Route::get('current-user', 'currentUser');
});

Route::controller(FavoritesController::class)->group(function () {
    Route::get('favorites', 'index');
    Route::get('favorites/{favorite}', 'show');
    Route::post('favorites', 'store');
    Route::delete('favorites/{favorite}', 'destroy');
});

Route::controller(MessagesController::class)->group(function () {
    Route::get('messages', 'index');
    Route::get('messages/{message}', 'show');
    Route::post('messages', 'store');
    Route::patch('messages/{message}', 'update');
    Route::delete('messages/{message}', 'destroy');
});

Route::controller(UserController::class)->group(function () {
    Route::get('users', 'index');
    Route::get('users/{user}', 'show');
    Route::post('users', 'store');
    Route::patch('users/{user}', 'update');
    Route::delete('users/{user}', 'destroy');
});

Route::controller(RoutesController::class)->group(function () {
    Route::get('routes', 'index');
    Route::get('routes/{route}', 'show');
    Route::get('routes/info/{route}', 'getOneRoute');
});

Route::apiResource("agencies", AgencyController::class);
Route::apiResource("calendars", CalendarController::class);
Route::apiResource("calendardates", CalendarDatesController::class);
Route::apiResource("frequencies", FrequenciesController::class);
Route::apiResource("shapes", ShapesController::class);
Route::apiResource("stops", StopsController::class);
Route::apiResource("stoptimes", StopTimesController::class);
Route::apiResource("trips", TripsController::class);
