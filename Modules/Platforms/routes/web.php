<?php

use Illuminate\Support\Facades\Route;
use Modules\Platforms\Http\Controllers\PlatformsController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('platforms', PlatformsController::class)->names('platforms');
});
