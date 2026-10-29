<?php

use App\Http\Controllers\Api\V1\AuthApiController;
use App\Http\Controllers\Api\V1\ShipmentApiController;
use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    return response()->json([
        'status' => 'operational',
        'platform' => 'EcoHorizon Global REST API v1',
        'emissions_engine' => 'GLEC Framework v3.0 Active',
        'timestamp' => now()->toIso8601String(),
    ]);
});

// V1 API Namespace
Route::prefix('v1')->group(function () {

    // Public / Unauthenticated
    Route::post('/auth/login', [AuthApiController::class, 'login']);
    Route::get('/track/{tracking_number}', [ShipmentApiController::class, 'publicTrack']);
    Route::get('/reference/service-levels', [ShipmentApiController::class, 'serviceLevels']);
    Route::get('/reference/locations', [ShipmentApiController::class, 'locations']);
    Route::get('/reference/emission-factors', [ShipmentApiController::class, 'factors']);

    // Protected by Laravel Sanctum
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', [AuthApiController::class, 'logout']);
        Route::get('/auth/me', [AuthApiController::class, 'me']);

        Route::get('/shipments', [ShipmentApiController::class, 'index']);
        Route::post('/shipments', [ShipmentApiController::class, 'store']);
        Route::get('/shipments/{tracking_number}', [ShipmentApiController::class, 'show']);
        Route::get('/shipments/{tracking_number}/events', [ShipmentApiController::class, 'events']);
        Route::post('/shipments/{tracking_number}/events', [ShipmentApiController::class, 'recordEvent']);
        Route::get('/shipments/{tracking_number}/exceptions', [ShipmentApiController::class, 'exceptions']);
        Route::get('/shipments/{tracking_number}/emissions', [ShipmentApiController::class, 'emissions']);
    });
});
