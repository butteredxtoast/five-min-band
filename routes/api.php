<?php

use App\Http\Controllers\Api\ParticipantController;
use App\Http\Controllers\Api\TestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Participant routes
Route::prefix('participant')->group(function () {
    Route::post('/', [ParticipantController::class, 'store']);
    Route::post('/random', [ParticipantController::class, 'random']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
