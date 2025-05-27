<?php

use Illuminate\Support\Facades\Route;
use Morasoft\ArtisanUI\Http\Controllers\ArtisanGuiController;

Route::middleware('web')->group(function () {
    Route::get('/artisan-ui', [ArtisanGuiController::class, 'index'])->name('artisan.gui');
    Route::post('/artisan-ui', [ArtisanGuiController::class, 'execute'])->name('artisan.tools.execute');
});
