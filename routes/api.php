<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\AppointmentController;

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

// States 
Route::get('/states', [StateController::class, 'index']);

// Auth Controllers 
Route::middleware('auth:sanctum')->get('/get', function (Request $request) { return $request->user(); });
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Vehicle Controllers 
    Route::get('/getVehicle', [VehicleController::class, 'get']);
    Route::post('/vehicle', [VehicleController::class, 'store']);

    Route::get('/getAppointment', [AppointmentController::class, 'get']);
    Route::post('/appointment/{vehicle}', [AppointmentController::class, 'store']);
});
