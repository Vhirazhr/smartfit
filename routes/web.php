<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\SmartFitController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ArticleController;

// Landing page route
Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/home', [LandingController::class, 'index'])->name('home');
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');
Route::get('/articles', [ArticleController::class, 'articlesPage'])->name('articles.page');

Route::prefix('smartfit')->group(function () {
    Route::get('/start', [SmartFitController::class, 'start'])->name('smartfit.start');
    Route::get('/check-body-type', [SmartFitController::class, 'checkBodyType'])->name('smartfit.check');
    Route::post('/known-body-type', [SmartFitController::class, 'processKnownBodyType'])->name('smartfit.known');
    Route::get('/input-measurements', [SmartFitController::class, 'inputMeasurements'])->name('smartfit.input');
    Route::post('/calculate', [SmartFitController::class, 'calculate'])->name('smartfit.calculate');
    Route::get('/result', [SmartFitController::class, 'result'])->name('smartfit.result');
    Route::get('/select-body-type', [SmartFitController::class, 'selectBodyType'])->name('smartfit.select');
    Route::post('/known-body-type', [SmartFitController::class, 'processKnownBodyType'])->name('smartfit.known');
});
