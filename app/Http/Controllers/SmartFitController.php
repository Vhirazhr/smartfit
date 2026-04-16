<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBodyMeasurementRequest;
use App\Models\BodyMeasurement;
use App\Models\BodyMeasurementAttempt;
use App\Services\MorphotypeService;
use App\Services\RecommendationService;
use Illuminate\Http\Request;

class SmartFitController extends Controller
{
    public function __construct(
        private readonly MorphotypeService $morphotypeService,
        private readonly RecommendationService $recommendationService
    ) {
    }

    /**
     * Halaman start setelah klik Discover
     */
    public function start()
    {
        return view('smartfit.start');
    }
    
    /**
     * Halaman cek body type (sudah tau atau belum)
     */
    public function checkBodyType()
    {
        return view('smartfit.check-body-type');
    }
    
    /**
     * Halaman pilih body type + preferensi (setelah Yes, I know)
     */
    public function selectBodyType()
    {
        return view('smartfit.select-body-type');
    }
    
    /**
     * Proses dari user yang sudah tahu body type + preferensi
     */
    public function processKnownBodyType(Request $request)
    {
        $request->validate([
            'body_type' => 'required|in:Hourglass,Rectangle,Spoon,Triangle,Inverted Triangle',
            'style_preference' => 'required|in:Casual,Formal,Bohemian,Classic,Sporty',
        ]);

        $bodyType = $request->body_type;
        $stylePreference = $request->style_preference;
        $colorTone = $request->color_tone;

        $morphotype = $this->mapKnownBodyTypeToMorphotype($bodyType);
        $recommendationPayload = $this->recommendationService->forMorphotype($morphotype);
        $recommendationData = $recommendationPayload['recommendations'] ?? [];

        $descriptionMap = [
            'Hourglass' => 'Balanced bust and hips with a clearly defined, narrow waist.',
            'Rectangle' => 'Bust and hip are fairly equal with minimal waist definition.',
            'Spoon' => 'Hips wider than bust with a defined waist and rounded lower body.',
            'Triangle' => 'Hips wider than shoulders with a defined waist.',
            'Inverted Triangle' => 'Shoulders or bust wider than hips with minimal waist definition.',
        ];

        session([
            'body_type' => $bodyType,
            'style_preference' => $stylePreference,
            'color_tone' => $colorTone,
            'description' => $descriptionMap[$bodyType] ?? 'Body type profile is available for this selection.',
            'recommendation_focus' => $recommendationData['focus'] ?? '',
            'recommendation_tops' => $recommendationData['tops'] ?? [],
            'recommendation_bottoms' => $recommendationData['bottoms'] ?? [],
            'recommendations' => array_merge($recommendationData['tops'] ?? [], $recommendationData['bottoms'] ?? []),
            'avoid' => $recommendationData['avoid'] ?? [],
            'style_tip' => $this->getStyleTip($stylePreference),
            'color_tip' => $this->getColorTip($colorTone),
            'source' => 'manual'
        ]);

        return redirect()->route('smartfit.result');
    }
    
    /**
     * Halaman form input antropometri (forward chaining)
     */
    public function inputMeasurements()
    {
        return view('smartfit.input-measurements');
    }
    
    /**
     * Proses kalkulasi dengan forward chaining
     */
    public function calculate(StoreBodyMeasurementRequest $request)
    {
        $validated = $request->validated();

        $bust = (float) $validated['bust'];
        $waist = (float) $validated['waist'];
        $hip = (float) $validated['hip'];

        $classification = $this->morphotypeService->classify($bust, $waist, $hip);
        $recommendationPayload = $this->recommendationService->forMorphotype($classification['morphotype']);
        $recommendationData = $recommendationPayload['recommendations'] ?? [];

        $measurement = BodyMeasurement::create([
            'bust' => $bust,
            'waist' => $waist,
            'hip' => $hip,
            'bust_to_waist_ratio' => (float) $classification['ratios']['bust_to_waist'],
            'hip_to_waist_ratio' => (float) $classification['ratios']['hip_to_waist'],
            'morphotype' => $classification['morphotype'],
            'morphotype_label' => $classification['label'],
            'measurement_standard' => 'ISO 8559-1',
            'source' => 'smartfit_web_wizard',
            'ip_address' => $request->ip(),
            'user_agent' => (string) $request->userAgent(),
        ]);

        BodyMeasurementAttempt::create([
            'body_measurement_id' => $measurement->id,
            'bust' => $bust,
            'waist' => $waist,
            'hip' => $hip,
            'status' => 'accepted',
            'rejection_reasons' => null,
            'is_consistency_issue' => false,
            'measurement_standard' => 'ISO 8559-1',
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 512),
        ]);

        session([
            'body_measurement_id' => $measurement->id,
            'body_type' => $classification['label'],
            'description' => $recommendationData['focus'] ?? 'Measurement profile ready for styling recommendations.',
            'recommendation_focus' => $recommendationData['focus'] ?? '',
            'recommendation_tops' => $recommendationData['tops'] ?? [],
            'recommendation_bottoms' => $recommendationData['bottoms'] ?? [],
            'recommendations' => array_merge($recommendationData['tops'] ?? [], $recommendationData['bottoms'] ?? []),
            'avoid' => $recommendationData['avoid'] ?? [],
            'bw_ratio' => (float) $classification['ratios']['bust_to_waist'],
            'hw_ratio' => (float) $classification['ratios']['hip_to_waist'],
            'bust' => $bust,
            'waist' => $waist,
            'hip' => $hip,
            'measurement_standard' => 'ISO 8559-1',
            'source' => 'calculated'
        ]);

        return redirect()->route('smartfit.result');
    }
    
    /**
     * Halaman hasil rekomendasi
     */
    public function result()
    {
        if (!session('body_type')) {
            return redirect()->route('smartfit.start');
        }

        return view('smartfit.result', [
            'measurementId' => session('body_measurement_id'),
            'bodyType' => session('body_type'),
            'description' => session('description'),
            'recommendationFocus' => session('recommendation_focus'),
            'recommendationTops' => session('recommendation_tops', []),
            'recommendationBottoms' => session('recommendation_bottoms', []),
            'recommendations' => session('recommendations'),
            'avoid' => session('avoid'),
            'styleTip' => session('style_tip'),
            'colorTip' => session('color_tip'),
            'stylePreference' => session('style_preference'),
            'colorTone' => session('color_tone'),
            'bwRatio' => session('bw_ratio'),
            'hwRatio' => session('hw_ratio'),
            'bust' => session('bust'),
            'waist' => session('waist'),
            'hip' => session('hip'),
            'measurementStandard' => session('measurement_standard'),
            'source' => session('source')
        ]);
    }

    private function mapKnownBodyTypeToMorphotype(string $bodyType): string
    {
        return match ($bodyType) {
            'Hourglass' => 'hourglass',
            'Rectangle' => 'rectangle',
            'Spoon' => 'spoon',
            'Triangle' => 'triangle',
            'Inverted Triangle' => 'y_shape',
            default => 'undefined',
        };
    }

    private function getStyleTip(string $stylePreference): string
    {
        $styleTips = [
            'Casual' => 'Pair the silhouette with clean sneakers and a light outer layer for daily comfort.',
            'Formal' => 'Use a structured blazer and refined footwear to keep proportions polished.',
            'Bohemian' => 'Add texture and movement with layered accessories and fluid fabrics.',
            'Classic' => 'Choose timeless tailoring and neutral accents for balanced elegance.',
            'Sporty' => 'Keep lines practical with breathable layers and dynamic, supportive footwear.',
        ];

        return $styleTips[$stylePreference] ?? '';
    }

    private function getColorTip(string $colorTone): string
    {
        $colorTips = [
            'Light' => 'Soft ivory, cream, and pastel tones create a bright and clean profile.',
            'Bright' => 'Saturated red, cobalt, and emerald accents add high visual energy.',
            'Neutral' => 'Black, navy, beige, and gray keep styling versatile across occasions.',
            'Dark' => 'Deep charcoal, forest, and burgundy deliver a refined, anchored look.',
            'Earth' => 'Terracotta, olive, rust, and sand tones bring warm natural balance.',
        ];

        return $colorTips[$colorTone] ?? '';
    }
}