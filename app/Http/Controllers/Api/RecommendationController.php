<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClassifyMorphotypeRequest;
use App\Services\MorphotypeService;
use App\Services\RecommendationService;
use Illuminate\Http\JsonResponse;

class RecommendationController extends Controller
{
    public function __construct(
        private readonly MorphotypeService $morphotypeService,
        private readonly RecommendationService $recommendationService
    ) {
    }

    public function recommend(ClassifyMorphotypeRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $classification = $this->morphotypeService->classify(
            (float) $validated['bust'],
            (float) $validated['waist'],
            (float) $validated['hip']
        );

        $recommendation = $this->recommendationService->forMorphotype($classification['morphotype']);

        return response()->json([
            'data' => [
                'classification' => $classification,
                'recommendation' => $recommendation,
            ],
        ]);
    }
}