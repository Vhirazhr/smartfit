<?php

namespace App\Http\Controllers;

use App\Models\BodyMeasurement;
use App\Models\BodyMeasurementAttempt;
use Illuminate\Http\Request;

class BodyMeasurementController extends Controller
{
    public function index(Request $request)
    {
        $morphotype = $request->query('morphotype');

        $measurements = BodyMeasurement::query()
            ->forMorphotype($morphotype)
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $totalMeasurements = BodyMeasurement::count();
        $todayMeasurements = BodyMeasurement::query()->whereDate('created_at', now()->toDateString())->count();
        $avgBwRatio = (float) (BodyMeasurement::query()->avg('bust_to_waist_ratio') ?? 0);
        $avgHwRatio = (float) (BodyMeasurement::query()->avg('hip_to_waist_ratio') ?? 0);

        $morphotypeDistribution = BodyMeasurement::query()
            ->selectRaw('morphotype_label, COUNT(*) as total')
            ->groupBy('morphotype_label')
            ->orderByDesc('total')
            ->get();

        $attemptBase = BodyMeasurementAttempt::query();
        $totalAttempts = (clone $attemptBase)->count();
        $acceptedAttempts = (clone $attemptBase)->where('status', 'accepted')->count();
        $rejectedAttempts = (clone $attemptBase)->where('status', 'rejected')->count();
        $consistencyRejections = (clone $attemptBase)
            ->where('status', 'rejected')
            ->where('is_consistency_issue', true)
            ->count();

        $rejectionRate = $totalAttempts > 0
            ? round(($rejectedAttempts / $totalAttempts) * 100, 2)
            : 0;

        $consistencyRejectionRate = $rejectedAttempts > 0
            ? round(($consistencyRejections / $rejectedAttempts) * 100, 2)
            : 0;

        $latestRejections = BodyMeasurementAttempt::query()
            ->where('status', 'rejected')
            ->latest()
            ->limit(8)
            ->get();

        return view('smartfit.body-measurements', [
            'measurements' => $measurements,
            'selectedMorphotype' => $morphotype,
            'totalMeasurements' => $totalMeasurements,
            'todayMeasurements' => $todayMeasurements,
            'avgBwRatio' => $avgBwRatio,
            'avgHwRatio' => $avgHwRatio,
            'morphotypeDistribution' => $morphotypeDistribution,
            'totalAttempts' => $totalAttempts,
            'acceptedAttempts' => $acceptedAttempts,
            'rejectedAttempts' => $rejectedAttempts,
            'rejectionRate' => $rejectionRate,
            'consistencyRejections' => $consistencyRejections,
            'consistencyRejectionRate' => $consistencyRejectionRate,
            'latestRejections' => $latestRejections,
        ]);
    }
}
