<?php

namespace App\Services;

use App\Models\BodyMeasurement;
use App\Models\SmartFitUsageLog;
use Illuminate\Http\Request;

class SmartFitUsageLogger
{
    public function __construct(private readonly IpGeolocationService $ipGeolocationService) {}

    public function recordCalculated(Request $request, BodyMeasurement $measurement, array $classification): SmartFitUsageLog
    {
        return $this->record($request, [
            'flow_type' => SmartFitUsageLog::FLOW_CALCULATED,
            'body_measurement_id' => $measurement->id,
            'body_type' => $classification['label'] ?? $measurement->morphotype_label,
            'morphotype' => $classification['morphotype'] ?? $measurement->morphotype,
            'bust' => $measurement->bust,
            'waist' => $measurement->waist,
            'hip' => $measurement->hip,
            'bust_to_waist_ratio' => $measurement->bust_to_waist_ratio,
            'hip_to_waist_ratio' => $measurement->hip_to_waist_ratio,
        ]);
    }

    public function recordKnown(
        Request $request,
        string $bodyType,
        string $morphotype,
        ?string $stylePreference,
        ?string $colorTone
    ): SmartFitUsageLog {
        return $this->record($request, [
            'flow_type' => SmartFitUsageLog::FLOW_KNOWN,
            'body_type' => $bodyType,
            'morphotype' => $morphotype,
            'style_preference' => $stylePreference,
            'color_tone' => $colorTone,
        ]);
    }

    public function updateStylePreference(?int $usageLogId, string $stylePreference, ?string $colorTone = null): void
    {
        if ($usageLogId === null || $usageLogId <= 0) {
            return;
        }

        SmartFitUsageLog::query()
            ->whereKey($usageLogId)
            ->update([
                'style_preference' => $stylePreference,
                'color_tone' => $colorTone,
            ]);
    }

    private function record(Request $request, array $payload): SmartFitUsageLog
    {
        $location = $this->ipGeolocationService->resolve($request);

        return SmartFitUsageLog::query()->create(array_merge($payload, $location, [
            'user_agent' => substr((string) $request->userAgent(), 0, 512),
        ]));
    }
}
