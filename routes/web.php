<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;

// Landing page route
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Nanti untuk sistem pakar
/*
Route::prefix('smartfit')->group(function () {
    Route::get('/analyze', [SmartFitController::class, 'index'])->name('smartfit.analyze');
    Route::post('/calculate', [SmartFitController::class, 'calculate'])->name('smartfit.calculate');
    Route::get('/result/{id}', [SmartFitController::class, 'result'])->name('smartfit.result');
});
*/