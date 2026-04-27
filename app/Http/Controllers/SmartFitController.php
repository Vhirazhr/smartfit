<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBodyMeasurementRequest;
use App\Models\BodyMeasurement;
use App\Models\BodyMeasurementAttempt;
use App\Models\FashionItem;
use App\Models\FashionItemStore;
use App\Services\MorphotypeService;
use App\Services\RecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class SmartFitController extends Controller
{
    public function __construct(
        private readonly MorphotypeService $morphotypeService,
        private readonly RecommendationService $recommendationService
    ) {}

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
        return view('smartfit.select-body-type', [
            'manualBodyTypes' => config('smartfit.manual_body_types', []),
            'descriptions' => config('smartfit.descriptions', []),
        ]);
    }

    /**
     * Proses dari user yang sudah tahu body type + preferensi
     */
    public function processKnownBodyType(Request $request)
    {
        $manualBodyTypes = array_keys(config('smartfit.manual_body_types', []));

        $request->validate([
            'body_type' => ['required', Rule::in($manualBodyTypes)],
            'style_preference' => 'required|in:Casual,Formal,Bohemian,Classic,Sporty',
        ]);

        $bodyType = $request->body_type;
        $stylePreference = $request->style_preference;
        $colorTone = $request->color_tone;

        $morphotype = $this->mapKnownBodyTypeToMorphotype($bodyType);
        $recommendationPayload = $this->recommendationService->forMorphotype($morphotype);
        $recommendationData = $recommendationPayload['recommendations'] ?? [];
        $recommendationData = $this->applyStylePreferenceToRecommendations($recommendationData, $stylePreference);

        $descriptionMap = config('smartfit.descriptions', []);

        session([
            'body_type' => $bodyType,
            'morphotype' => $morphotype,
            'style_preference' => $stylePreference,
            'color_tone' => $colorTone,
            'description' => $descriptionMap[$morphotype] ?? 'Body type profile is available for this selection.',
            'recommendation_focus' => $recommendationData['focus'] ?? '',
            'recommendation_tops' => $recommendationData['tops'] ?? [],
            'recommendation_bottoms' => $recommendationData['bottoms'] ?? [],
            'recommendations' => array_merge($recommendationData['tops'] ?? [], $recommendationData['bottoms'] ?? []),
            'avoid' => $recommendationData['avoid'] ?? [],
            'style_tip' => $this->getStyleTip($stylePreference),
            'color_tip' => $this->getColorTip((string) $colorTone),
            'source' => 'manual',
        ]);

        return redirect()
            ->route('smartfit.result')
            ->with('recommendation_updated', 'Recommendation has been updated based on your selected style preference.');
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
            'morphotype' => $classification['morphotype'],
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
            'source' => 'calculated',
        ]);

        return redirect()->route('smartfit.result');
    }

    /**
     * Halaman hasil rekomendasi
     */
    public function result()
    {
        if (! session('body_type')) {
            return redirect()->route('smartfit.start');
        }

        $bodyType = (string) session('body_type');
        $source = (string) session('source');
        $visual = $this->buildBodyVisual(
            session('bust') !== null ? (float) session('bust') : null,
            session('waist') !== null ? (float) session('waist') : null,
            session('hip') !== null ? (float) session('hip') : null,
            $bodyType
        );

        return view('smartfit.result', [
            'measurementId' => session('body_measurement_id'),
            'bodyType' => $bodyType,
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
            'source' => $source,
            'bodyVisual' => $visual,
        ]);
    }

    public function recommendation()
    {
        if (! session('body_type')) {
            return redirect()->route('smartfit.start');
        }

        if (! session('style_preference')) {
            return redirect()->route('smartfit.result')->with('recommendation_missing', 'Please select a style preference first.');
        }

        $bodyType = (string) session('body_type');
        $systemRecommendation = $this->buildSystemRecommendation($bodyType, session('style_preference'));

        if (! $systemRecommendation) {
            return redirect()->route('smartfit.result')->with('recommendation_missing', 'Recommendation data is not available yet. Please try another style.');
        }

        return view('smartfit.recommendation', [
            'bodyType' => $bodyType,
            'stylePreference' => session('style_preference'),
            'systemRecommendation' => $systemRecommendation,
        ]);
    }

    public function updateStylePreference(Request $request)
    {
        if (! session('body_type')) {
            return redirect()->route('smartfit.start');
        }

        $validated = $request->validate([
            'style_preference' => 'required|in:Casual,Formal,Bohemian,Classic,Sporty',
        ]);

        $stylePreference = (string) $validated['style_preference'];
        $bodyType = (string) session('body_type');
        $morphotype = $this->mapBodyTypeToMorphotype($bodyType);
        $recommendationPayload = $this->recommendationService->forMorphotype($morphotype);
        $recommendationData = $recommendationPayload['recommendations'] ?? [];
        $recommendationData = $this->applyStylePreferenceToRecommendations($recommendationData, $stylePreference);

        session([
            'style_preference' => $stylePreference,
            'recommendation_focus' => $recommendationData['focus'] ?? '',
            'recommendation_tops' => $recommendationData['tops'] ?? [],
            'recommendation_bottoms' => $recommendationData['bottoms'] ?? [],
            'recommendations' => array_merge($recommendationData['tops'] ?? [], $recommendationData['bottoms'] ?? []),
            'avoid' => $recommendationData['avoid'] ?? [],
            'style_tip' => $this->getStyleTip($stylePreference),
            'description' => $recommendationData['focus'] ?? session('description'),
        ]);

        return redirect()
            ->route('smartfit.recommendation')
            ->with('recommendation_updated', 'Recommendation has been updated based on your selected style preference.');
    }

    private function mapKnownBodyTypeToMorphotype(string $bodyType): string
    {
        return $this->resolveMorphotypeKey($bodyType);
    }

    private function mapBodyTypeToMorphotype(string $bodyType): string
    {
        return $this->resolveMorphotypeKey($bodyType);
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

    private function applyStylePreferenceToRecommendations(array $recommendationData, string $stylePreference): array
    {
        $styleOverlays = [
            'Casual' => [
                'focus' => 'Prioritize easy-to-wear pieces and relaxed layering for everyday comfort.',
                'tops' => ['Relaxed tees with clean fit', 'Soft knit cardigans'],
                'bottoms' => ['Comfort denim cuts', 'Casual straight pants'],
                'avoid' => ['Outfits that are too rigid for daily movement'],
            ],
            'Formal' => [
                'focus' => 'Refine the silhouette with structured tailoring and polished details.',
                'tops' => ['Structured blazers', 'Clean button-up shirts'],
                'bottoms' => ['Tailored trousers', 'Elegant midi skirts'],
                'avoid' => ['Overly casual fabrics for formal settings'],
            ],
            'Bohemian' => [
                'focus' => 'Use flow, texture, and layered elements while keeping body balance.',
                'tops' => ['Flowy blouses with texture', 'Layer-friendly outerwear'],
                'bottoms' => ['Soft drape skirts', 'Relaxed wide-leg pants'],
                'avoid' => ['Rigid silhouettes without movement'],
            ],
            'Classic' => [
                'focus' => 'Keep the look timeless with clean cuts and balanced proportions.',
                'tops' => ['Minimal tailored tops', 'Refined neutral blouses'],
                'bottoms' => ['Straight-cut trousers', 'Classic pencil silhouettes'],
                'avoid' => ['Trend-heavy pieces that break visual balance'],
            ],
            'Sporty' => [
                'focus' => 'Build dynamic outfits with practical cuts and breathable materials.',
                'tops' => ['Performance-inspired tops', 'Athleisure zip jackets'],
                'bottoms' => ['Jogger-inspired cuts', 'Flexible active pants'],
                'avoid' => ['Heavy fabrics that limit movement'],
            ],
        ];

        if (! isset($styleOverlays[$stylePreference])) {
            return $recommendationData;
        }

        $overlay = $styleOverlays[$stylePreference];

        $recommendationData['focus'] = $overlay['focus'];
        $recommendationData['tops'] = $this->mergeUniqueStrings($overlay['tops'], $recommendationData['tops'] ?? []);
        $recommendationData['bottoms'] = $this->mergeUniqueStrings($overlay['bottoms'], $recommendationData['bottoms'] ?? []);
        $recommendationData['avoid'] = $this->mergeUniqueStrings($recommendationData['avoid'] ?? [], $overlay['avoid']);

        return $recommendationData;
    }

    private function mergeUniqueStrings(array $first, array $second): array
    {
        return array_values(array_unique(array_merge($first, $second)));
    }

    private function buildSystemRecommendation(string $bodyType, ?string $stylePreference): ?array
    {
        $styleKey = strtolower((string) ($stylePreference ?? ''));
        if ($styleKey === '') {
            $styleKey = 'formal';
        }

        $morphotype = $this->resolveMorphotypeKey($bodyType);
        $items = $this->resolveRecommendationItemsFromDatabase($morphotype, $styleKey);

        if ($items->isEmpty()) {
            return null;
        }

        $products = $items
            ->map(fn (FashionItem $item): array => $this->mapFashionItemToSystemProduct($item))
            ->values()
            ->all();

        return [
            'main_product' => $products[0],
            'other_products' => array_slice($products, 1),
            'tips' => $this->buildSystemRecommendationTips($morphotype),
            'style_key' => $styleKey,
            'body_key' => $morphotype,
        ];
    }

    private function resolveRecommendationItemsFromDatabase(string $morphotype, string $styleKey): Collection
    {
        $items = $this->queryRecommendationItemsByMorphotypes([$morphotype], $morphotype, $styleKey);

        if ($items->isEmpty()) {
            $items = $this->queryRecommendationItemsByMorphotypes($this->fallbackMorphotypes($morphotype), $morphotype, $styleKey);
        }

        if ($items->isEmpty()) {
            $items = FashionItem::query()
                ->with([
                    'stores' => fn ($query) => $query->ordered(),
                ])
                ->active()
                ->orderByRaw('CASE WHEN style_preference = ? THEN 0 WHEN style_preference IS NULL THEN 1 ELSE 2 END', [$styleKey])
                ->ordered()
                ->limit(12)
                ->get();
        }

        return $items;
    }

    private function queryRecommendationItemsByMorphotypes(array $morphotypes, string $primaryMorphotype, string $styleKey): Collection
    {
        $sanitizedMorphotypes = array_values(array_unique(array_filter($morphotypes, fn ($value) => filled($value))));

        if (empty($sanitizedMorphotypes)) {
            return collect();
        }

        return FashionItem::query()
            ->with([
                'stores' => fn ($query) => $query->ordered(),
            ])
            ->active()
            ->whereIn('body_type', $sanitizedMorphotypes)
            ->orderByRaw('CASE WHEN body_type = ? THEN 0 ELSE 1 END', [$primaryMorphotype])
            ->orderByRaw('CASE WHEN style_preference = ? THEN 0 WHEN style_preference IS NULL THEN 1 ELSE 2 END', [$styleKey])
            ->ordered()
            ->limit(12)
            ->get();
    }

    private function fallbackMorphotypes(string $morphotype): array
    {
        return match ($morphotype) {
            'spoon', 'triangle' => ['spoon', 'triangle', 'hourglass'],
            'y_shape', 'inverted_triangle', 'inverted_u' => ['y_shape', 'inverted_triangle', 'inverted_u', 'hourglass'],
            'rectangle', 'u', 'diamond' => ['rectangle', 'u', 'diamond', 'hourglass'],
            'hourglass' => ['hourglass', 'rectangle'],
            default => ['hourglass', 'rectangle', 'spoon', 'y_shape'],
        };
    }

    private function mapFashionItemToSystemProduct(FashionItem $item): array
    {
        $stores = $item->stores
            ->map(fn (FashionItemStore $store): array => [
                'name' => $store->store_name,
                'link' => $store->store_link,
            ])
            ->values()
            ->all();

        if (empty($stores) && filled($item->purchase_link)) {
            $stores[] = [
                'name' => 'Store Link',
                'link' => $item->purchase_link,
            ];
        }

        $primaryStore = $stores[0] ?? null;

        return [
            'id' => $item->id,
            'name' => $item->title,
            'description' => (string) ($item->description ?? ''),
            'main_image' => $item->display_image_url,
            'detail_images' => [$item->display_image_url],
            'price' => null,
            'shop' => $primaryStore['name'] ?? 'Marketplace',
            'shop_url' => $primaryStore['link'] ?? $item->purchase_link,
            'stores' => $stores,
        ];
    }

    private function buildSystemRecommendationTips(string $morphotype): array
    {
        $recommendationPayload = $this->recommendationService->forMorphotype($morphotype);
        $recommendationData = $recommendationPayload['recommendations'] ?? [];

        $tips = [];
        $styleTip = (string) session('style_tip', '');

        if ($styleTip !== '') {
            $tips[] = [
                'icon' => 'fa-solid fa-wand-magic-sparkles',
                'text' => $styleTip,
            ];
        }

        $focus = (string) ($recommendationData['focus'] ?? '');
        if ($focus !== '') {
            $tips[] = [
                'icon' => 'fa-solid fa-bullseye',
                'text' => $focus,
            ];
        }

        foreach (array_slice($recommendationData['tops'] ?? [], 0, 2) as $topTip) {
            $tips[] = [
                'icon' => 'fa-solid fa-shirt',
                'text' => $topTip,
            ];
        }

        foreach (array_slice($recommendationData['bottoms'] ?? [], 0, 2) as $bottomTip) {
            $tips[] = [
                'icon' => 'fa-solid fa-shoe-prints',
                'text' => $bottomTip,
            ];
        }

        if (empty($tips)) {
            $tips[] = [
                'icon' => 'fa-solid fa-lightbulb',
                'text' => 'Choose pieces that keep your silhouette balanced and comfortable.',
            ];
        }

        return array_values(array_slice($tips, 0, 4));
    }

    private function buildBodyVisual(?float $bust, ?float $waist, ?float $hip, string $bodyType): array
    {
        if (($bust ?? 0) <= 0 || ($waist ?? 0) <= 0 || ($hip ?? 0) <= 0) {
            [$bust, $waist, $hip] = $this->presetMeasurementsByBodyType($bodyType);
        }

        $max = max($bust, $waist, $hip, 1);
        $minWidth = 48;
        $maxWidth = 100;

        $normalize = static function (float $value) use ($max, $minWidth, $maxWidth): int {
            return (int) round($minWidth + (($value / $max) * ($maxWidth - $minWidth)));
        };

        return [
            'shape_key' => $this->mapBodyTypeToShapeKey($bodyType),
            'bust_width' => $normalize($bust),
            'waist_width' => $normalize($waist),
            'hip_width' => $normalize($hip),
            'bust_value' => round($bust, 1),
            'waist_value' => round($waist, 1),
            'hip_value' => round($hip, 1),
        ];
    }

    private function mapBodyTypeToShapeKey(string $bodyType): string
    {
        return match ($this->resolveMorphotypeKey($bodyType)) {
            'hourglass' => 'hourglass',
            'spoon' => 'spoon',
            'triangle' => 'triangle',
            'y_shape', 'inverted_triangle', 'inverted_u' => 'inverted',
            default => 'rectangle',
        };
    }

    private function resolveMorphotypeKey(string $bodyType): string
    {
        $normalized = strtolower(trim($bodyType));
        if ($normalized === '') {
            return 'undefined';
        }

        $bodyTypes = config('smartfit.body_types', []);
        if (in_array($normalized, $bodyTypes, true)) {
            return $normalized;
        }

        foreach (config('smartfit.manual_body_types', []) as $label => $key) {
            if (strtolower($label) === $normalized) {
                return $key;
            }
        }

        foreach (config('smartfit.labels', []) as $key => $label) {
            if (strtolower($label) === $normalized) {
                return $key;
            }
        }

        $aliases = [
            'triangle (pear)' => 'triangle',
            'pear' => 'triangle',
            'y shape' => 'y_shape',
            'inverted' => 'inverted_triangle',
            'u shape' => 'u',
        ];

        return $aliases[$normalized] ?? 'undefined';
    }

    private function presetMeasurementsByBodyType(string $bodyType): array
    {
        return match ($this->mapBodyTypeToShapeKey($bodyType)) {
            'hourglass' => [92.0, 68.0, 92.0],
            'rectangle' => [90.0, 84.0, 90.0],
            'spoon' => [84.0, 70.0, 98.0],
            'triangle' => [82.0, 72.0, 98.0],
            'inverted' => [98.0, 74.0, 84.0],
            default => [90.0, 80.0, 90.0],
        };
    }
}
