<?php

use Illuminate\Support\Facades\Route;
use Modules\Platforms\Http\Controllers\PlatformsController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('platforms', PlatformsController::class)->names('platforms');
});
