<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;

// Landing page route
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Backward-compatible alias for templates or links using route('home')
Route::get('/home', [LandingController::class, 'index'])->name('home');

// Nanti untuk sistem pakar
/*
Route::prefix('smartfit')->group(function () {
    Route::get('/analyze', [SmartFitController::class, 'index'])->name('smartfit.analyze');
    Route::post('/calculate', [SmartFitController::class, 'calculate'])->name('smartfit.calculate');
    Route::get('/result/{id}', [SmartFitController::class, 'result'])->name('smartfit.result');
});
*/
