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
        $recommendationData = $this->applyStylePreferenceToRecommendations($recommendationData, $stylePreference);

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
        if (!session('body_type')) {
            return redirect()->route('smartfit.start');
        }

        if (!session('style_preference')) {
            return redirect()->route('smartfit.result')->with('recommendation_missing', 'Please select a style preference first.');
        }

        $bodyType = (string) session('body_type');
        $systemRecommendation = $this->buildSystemRecommendation($bodyType, session('style_preference'));

        if (!$systemRecommendation) {
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
        if (!session('body_type')) {
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
        return match ($bodyType) {
            'Hourglass' => 'hourglass',
            'Rectangle' => 'rectangle',
            'Spoon' => 'spoon',
            'Triangle' => 'triangle',
            'Inverted Triangle' => 'y_shape',
            default => 'undefined',
        };
    }

    private function mapBodyTypeToMorphotype(string $bodyType): string
    {
        return match ($this->mapBodyTypeToShapeKey($bodyType)) {
            'hourglass' => 'hourglass',
            'rectangle' => 'rectangle',
            'spoon' => 'spoon',
            'triangle' => 'triangle',
            'inverted' => 'y_shape',
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

        if (!isset($styleOverlays[$stylePreference])) {
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
            return null;
        }

        $bodyKey = $this->mapBodyTypeToProductKey($bodyType);
        $productCatalog = $this->productRecommendationCatalog();
        $tipsCatalog = $this->stylingTipsCatalog();

        $products = $productCatalog[$bodyKey][$styleKey]
            ?? $productCatalog[$bodyKey]['formal']
            ?? $productCatalog['hourglass'][$styleKey]
            ?? $productCatalog['hourglass']['formal']
            ?? [];

        if (empty($products)) {
            return null;
        }

        return [
            'main_product' => $products[0],
            'other_products' => array_slice($products, 1),
            'tips' => $tipsCatalog[$bodyKey] ?? ($tipsCatalog['hourglass'] ?? []),
            'style_key' => $styleKey,
            'body_key' => $bodyKey,
        ];
    }

    private function mapBodyTypeToProductKey(string $bodyType): string
    {
        return match ($this->mapBodyTypeToShapeKey($bodyType)) {
            'hourglass' => 'hourglass',
            'rectangle' => 'rectangle',
            'spoon', 'triangle' => 'pear',
            'inverted' => 'inverted_triangle',
            default => 'hourglass',
        };
    }

    private function productRecommendationCatalog(): array
    {
        return [
            'hourglass' => [
                'formal' => [
                    [
                        'name' => 'Elegant Belted Blazer Dress',
                        'description' => 'A sophisticated blazer dress with a belt to accentuate your natural waist.',
                        'main_image' => 'images/hourglass/formal/H_formal1.jpg',
                        'detail_images' => ['images/hourglass/formal/H_formal1.jpg'],
                        'price' => '$89.99',
                        'shop' => 'ZARA',
                        'shop_url' => 'https://www.zara.com/',
                    ],
                    [
                        'name' => 'Wrap Midi Dress',
                        'description' => 'Flattering wrap dress that hugs your curves in all the right places.',
                        'main_image' => 'images/hourglass/formal/H_formal2.jpg',
                        'detail_images' => ['images/hourglass/formal/H_formal2.jpg'],
                        'price' => '$75.00',
                        'shop' => 'MANGO',
                        'shop_url' => 'https://shop.mango.com/',
                    ],
                ],
                'casual' => [
                    [
                        'name' => 'Fitted Ribbed T-Shirt',
                        'description' => 'Comfortable fitted t-shirt that follows your natural curves.',
                        'main_image' => 'images/hourglass/casual/H_casual1.jpg',
                        'detail_images' => ['images/hourglass/casual/H_casual1.jpg'],
                        'price' => '$24.99',
                        'shop' => 'H&M',
                        'shop_url' => 'https://www2.hm.com/',
                    ],
                    [
                        'name' => 'Soft Waist-Shape Cardigan',
                        'description' => 'Layering cardigan that keeps your waistline defined for daily wear.',
                        'main_image' => 'images/hourglass/casual/H_casual2.jpg',
                        'detail_images' => ['images/hourglass/casual/H_casual2.jpg'],
                        'price' => '$39.99',
                        'shop' => 'UNIQLO',
                        'shop_url' => 'https://www.uniqlo.com/',
                    ],
                ],
            ],
            'pear' => [
                'formal' => [
                    [
                        'name' => 'A-Line Midi Dress',
                        'description' => 'Flattering A-line dress that skims over hips with elegant balance.',
                        'main_image' => 'images/pear/formal/P_formal1.jpg',
                        'detail_images' => ['images/pear/formal/P_formal1.jpg'],
                        'price' => '$79.99',
                        'shop' => 'MANGO',
                        'shop_url' => 'https://shop.mango.com/',
                    ],
                ],
                'casual' => [
                    [
                        'name' => 'Boat Neck Casual Top',
                        'description' => 'Adds visual width to upper body while staying relaxed and easy.',
                        'main_image' => 'images/pear/casual/P_casual1.jpg',
                        'detail_images' => ['images/pear/casual/P_casual1.jpg'],
                        'price' => '$29.99',
                        'shop' => 'H&M',
                        'shop_url' => 'https://www2.hm.com/',
                    ],
                ],
            ],
            'rectangle' => [
                'formal' => [
                    [
                        'name' => 'Peplum Top with Belt',
                        'description' => 'Creates the illusion of curves with peplum detail and waist focus.',
                        'main_image' => 'images/rectangle/formal/R_formal1.jpg',
                        'detail_images' => ['images/rectangle/formal/R_formal1.jpg'],
                        'price' => '$59.99',
                        'shop' => 'H&M',
                        'shop_url' => 'https://www2.hm.com/',
                    ],
                ],
                'casual' => [
                    [
                        'name' => 'Layered Knit Set',
                        'description' => 'Casual layering pieces to add contour and shape naturally.',
                        'main_image' => 'images/rectangle/casual/R_casual1.jpg',
                        'detail_images' => ['images/rectangle/casual/R_casual1.jpg'],
                        'price' => '$44.99',
                        'shop' => 'UNIQLO',
                        'shop_url' => 'https://www.uniqlo.com/',
                    ],
                ],
            ],
            'inverted_triangle' => [
                'formal' => [
                    [
                        'name' => 'V-Neck Blazer',
                        'description' => 'Deep V-neckline balances broad shoulders with polished structure.',
                        'main_image' => 'images/inverted_triangle/formal/IT_formal1.jpg',
                        'detail_images' => ['images/inverted_triangle/formal/IT_formal1.jpg'],
                        'price' => '$89.99',
                        'shop' => 'Banana Republic',
                        'shop_url' => 'https://bananarepublic.gap.com/',
                    ],
                ],
                'casual' => [
                    [
                        'name' => 'Soft V-Neck Everyday Top',
                        'description' => 'Simple casual top that softens upper lines and keeps proportions balanced.',
                        'main_image' => 'images/inverted_triangle/casual/IT_casual1.jpg',
                        'detail_images' => ['images/inverted_triangle/casual/IT_casual1.jpg'],
                        'price' => '$34.99',
                        'shop' => 'ZARA',
                        'shop_url' => 'https://www.zara.com/',
                    ],
                ],
            ],
        ];
    }

    private function stylingTipsCatalog(): array
    {
        return [
            'hourglass' => [
                ['icon' => 'fa-solid fa-tshirt', 'text' => 'Highlight your waist with belted styles and fitted silhouettes.'],
                ['icon' => 'fa-solid fa-arrow-up', 'text' => 'Choose V-necklines to elongate your upper body balance.'],
                ['icon' => 'fa-solid fa-heart', 'text' => 'Wrap dresses and peplum tops are strong everyday options.'],
            ],
            'pear' => [
                ['icon' => 'fa-solid fa-arrow-up', 'text' => 'Draw attention upward with statement necklines and shoulder details.'],
                ['icon' => 'fa-solid fa-tshirt', 'text' => 'Use A-line bottoms to smooth lower-body proportions.'],
                ['icon' => 'fa-solid fa-circle', 'text' => 'Keep bottom colors darker for cleaner visual balance.'],
            ],
            'rectangle' => [
                ['icon' => 'fa-solid fa-link', 'text' => 'Create curves with belts and strategic layering.'],
                ['icon' => 'fa-solid fa-circle', 'text' => 'Add soft volume around bust and hip areas.'],
                ['icon' => 'fa-solid fa-bolt', 'text' => 'Use waist-focused cuts to break straight vertical lines.'],
            ],
            'inverted_triangle' => [
                ['icon' => 'fa-solid fa-arrow-down', 'text' => 'Balance upper width by adding texture on bottom pieces.'],
                ['icon' => 'fa-solid fa-tshirt', 'text' => 'Prefer softer shoulder construction with V-neck profiles.'],
                ['icon' => 'fa-solid fa-layer-group', 'text' => 'Choose clean upper layers and expressive lower silhouettes.'],
            ],
        ];
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
        return match (strtolower(trim($bodyType))) {
            'hourglass' => 'hourglass',
            'rectangle' => 'rectangle',
            'spoon' => 'spoon',
            'triangle', 'triangle (pear)', 'pear' => 'triangle',
            'inverted triangle', 'y shape', 'y_shape', 'inverted u' => 'inverted',
            default => 'rectangle',
        };
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