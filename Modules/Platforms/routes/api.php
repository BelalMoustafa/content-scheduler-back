<?php

use Illuminate\Support\Facades\Route;
use Modules\Platforms\Http\Controllers\PlatformController;
use Modules\Platforms\Http\Controllers\Api\Admin\PlatformController as AdminPlatformController;

Route::middleware('auth:sanctum')->group(function () {
    // User routes
    Route::prefix('platforms')->group(function () {
        Route::get('/', [PlatformController::class, 'index']);
        Route::post('toggle', [PlatformController::class, 'toggle']);
    });

    // Admin routes
    Route::middleware('role:admin')->prefix('admin/platforms')->group(function () {
        Route::get('/', [AdminPlatformController::class, 'index']);
        Route::get('/{id}', [AdminPlatformController::class, 'show']);
        Route::post('/', [AdminPlatformController::class, 'store']);
        Route::put('/{id}', [AdminPlatformController::class, 'update']);
        Route::delete('/{id}', [AdminPlatformController::class, 'destroy']);
        Route::post('/toggle-user', [AdminPlatformController::class, 'toggleUserPlatform']);
        Route::get('/analytics', [AdminPlatformController::class, 'analytics']);
    });
});
