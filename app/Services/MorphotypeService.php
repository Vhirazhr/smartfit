<?php

namespace App\Services;

use InvalidArgumentException;

class MorphotypeService
{
    public function classify(float $bust, float $waist, float $hip): array
    {
        if ($waist <= 0) {
            throw new InvalidArgumentException('Waist must be greater than 0.');
        }

        $bRatio = $bust / $waist;
        $hRatio = $hip / $waist;
        $morphotype = $this->resolveMorphotype($bRatio, $hRatio);

        return [
            'morphotype' => $morphotype,
            'label' => config('smartfit.labels.'.$morphotype, 'Undefined'),
            'ratios' => [
                'bust_to_waist' => round($bRatio, 4),
                'hip_to_waist' => round($hRatio, 4),
            ],
            'input' => [
                'bust' => $bust,
                'waist' => $waist,
                'hip' => $hip,
            ],
        ];
    }

    private function resolveMorphotype(float $bRatio, float $hRatio): string
    {
        $bustLower = (float) config('smartfit.thresholds.bust.lower', 0.97);
        $bustUpper = (float) config('smartfit.thresholds.bust.upper', 1.24);
        $hipLower = (float) config('smartfit.thresholds.hip.lower', 0.89);
        $hipUpper = (float) config('smartfit.thresholds.hip.upper', 1.20);

        if ($bRatio > $bustUpper && $hRatio > $hipUpper) {
            return 'hourglass';
        }

        if ($bRatio > $bustUpper && $hRatio > $hipLower && $hRatio < $hipUpper) {
            return 'y_shape';
        }

        if ($bRatio < $bustLower && $hRatio > $hipUpper) {
            return 'triangle';
        }

        if ($bRatio > $bustLower && $bRatio < $bustUpper && $hRatio > $hipUpper) {
            return 'spoon';
        }

        if ($bRatio > $bustLower && $bRatio < $bustUpper && $hRatio > $hipLower && $hRatio < $hipUpper) {
            return 'rectangle';
        }

        if ($bRatio < $bustLower && $hRatio > $hipLower && $hRatio < $hipUpper) {
            return 'inverted_u';
        }

        return 'undefined';
    }
}