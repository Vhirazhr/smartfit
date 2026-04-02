<?php

use App\Http\Controllers\Api\MorphotypeController;
use App\Http\Controllers\Api\RecommendationController;
use Illuminate\Support\Facades\Route;

Route::post('/morphotype/classify', [MorphotypeController::class, 'classify']);
Route::post('/recommendations', [RecommendationController::class, 'recommend']);