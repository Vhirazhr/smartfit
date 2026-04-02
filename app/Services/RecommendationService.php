<?php

namespace App\Services;

class RecommendationService
{
    public function forMorphotype(string $morphotype): array
    {
        $all = config('smartfit.recommendations', []);
        $selected = $all[$morphotype] ?? ($all['undefined'] ?? []);

        return [
            'morphotype' => $morphotype,
            'recommendations' => $selected,
        ];
    }
}