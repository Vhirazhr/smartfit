<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClassifyMorphotypeRequest;
use App\Services\MorphotypeService;
use Illuminate\Http\JsonResponse;

class MorphotypeController extends Controller
{
    public function __construct(private readonly MorphotypeService $morphotypeService)
    {
    }

    public function classify(ClassifyMorphotypeRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $result = $this->morphotypeService->classify(
            (float) $validated['bust'],
            (float) $validated['waist'],
            (float) $validated['hip']
        );

        return response()->json([
            'data' => $result,
        ]);
    }
}