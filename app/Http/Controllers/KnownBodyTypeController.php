<?php

namespace App\Http\Controllers;

use App\Models\FashionItem;
use App\Models\FashionItemStore;
use App\Services\RecommendationService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KnownBodyTypeController extends Controller
{
    public function __construct(private readonly RecommendationService $recommendationService) {}

    public function selectBodyType()
    {
        return view('smartfit.select-body-type', [
            'manualBodyTypes' => config('smartfit.manual_body_types', []),
            'descriptions' => config('smartfit.descriptions', []),
        ]);
    }

    public function processKnownBodyType(Request $request)
    {
        $manualBodyTypes = config('smartfit.manual_body_types', []);
        $allowedBodyTypes = array_keys($manualBodyTypes);

        $request->validate([
            'body_type' => ['required', Rule::in($allowedBodyTypes)],
            'style_preference' => 'required|in:Casual,Formal,Bohemian,Classic,Sporty',
        ]);

        $bodyType = (string) $request->body_type;
        $stylePreference = (string) $request->style_preference;
        $colorTone = $request->color_tone ?? null;

        $morphotype = $manualBodyTypes[$bodyType] ?? 'undefined';
        $recommendationPayload = $this->recommendationService->forMorphotype($morphotype);
        $recommendationData = $recommendationPayload['recommendations'] ?? [];

        session([
            'body_type' => $bodyType,
            'morphotype' => $morphotype,
            'style_preference' => $stylePreference,
            'color_tone' => $colorTone,
            'description' => config('smartfit.descriptions.'.$morphotype, 'Body type profile is available for this selection.'),
            'recommendation_focus' => $recommendationData['focus'] ?? '',
            'recommendation_tops' => $recommendationData['tops'] ?? [],
            'recommendation_bottoms' => $recommendationData['bottoms'] ?? [],
            'recommendations' => array_merge($recommendationData['tops'] ?? [], $recommendationData['bottoms'] ?? []),
            'avoid' => $recommendationData['avoid'] ?? [],
            'style_tip' => $this->getStyleTip($stylePreference),
            'color_tip' => $this->getColorTip((string) $colorTone),
            'source' => 'known',
        ]);

        return redirect()->route('known.result');
    }

    public function result()
    {
        if (! session('body_type')) {
            return redirect()->route('known.select');
        }

        $morphotype = (string) session('morphotype', 'undefined');
        $stylePreference = (string) session('style_preference', 'Formal');
        $styleKey = strtolower($stylePreference);
        $recommendedItems = $this->resolveRecommendedItems($morphotype, $styleKey);
        $stylingTips = $this->buildStylingTips($morphotype);

        return view('smartfit.result-known', [
            'bodyType' => session('body_type'),
            'stylePreference' => $stylePreference,
            'colorTone' => session('color_tone'),
            'description' => session('description'),
            'recommendations' => session('recommendations'),
            'avoid' => session('avoid'),
            'styleTip' => session('style_tip'),
            'colorTip' => session('color_tip'),
            'source' => session('source'),
            'recommendedItems' => $recommendedItems,
            'stylingTips' => $stylingTips,
        ]);
    }

    private function getStyleTip(string $stylePreference): string
    {
        $styleTips = [
            'Casual' => 'Pair with sneakers and an easy outer layer for balanced daily style.',
            'Formal' => 'Use structured tailoring to keep proportions polished and clean.',
            'Bohemian' => 'Add layered textures and flowing pieces while keeping visual balance.',
            'Classic' => 'Keep the silhouette timeless with neat cuts and refined neutral accents.',
            'Sporty' => 'Choose breathable materials and practical cuts for dynamic movement.',
        ];

        return $styleTips[$stylePreference] ?? '';
    }

    private function getColorTip(string $colorTone): string
    {
        $colorTips = [
            'Light' => 'Soft ivory, cream, and pastel tones provide a bright profile.',
            'Bright' => 'Saturated red, cobalt, and emerald add confident contrast.',
            'Neutral' => 'Black, navy, beige, and gray keep outfits versatile.',
            'Dark' => 'Deep charcoal, forest, and burgundy offer a refined finish.',
            'Earth' => 'Terracotta, olive, rust, and sand bring warm natural balance.',
        ];

        return $colorTips[$colorTone] ?? '';
    }

    private function resolveRecommendedItems(string $morphotype, string $styleKey): array
    {
        $normalizedStyle = strtolower(trim($styleKey));
        if ($normalizedStyle === '') {
            $normalizedStyle = 'formal';
        }

        $items = $this->queryByMorphotypes([$morphotype], $morphotype, $normalizedStyle);

        if ($items->isEmpty()) {
            $items = $this->queryByMorphotypes($this->fallbackMorphotypes($morphotype), $morphotype, $normalizedStyle);
        }

        if ($items->isEmpty()) {
            $items = FashionItem::query()
                ->with([
                    'stores' => fn ($query) => $query->ordered(),
                ])
                ->active()
                ->orderByRaw('CASE WHEN style_preference = ? THEN 0 WHEN style_preference IS NULL THEN 1 ELSE 2 END', [$normalizedStyle])
                ->ordered()
                ->limit(12)
                ->get();
        }

        return $items
            ->map(function (FashionItem $item): array {
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
                    'shop' => $primaryStore['name'] ?? 'Marketplace',
                    'shopUrl' => $primaryStore['link'] ?? $item->purchase_link,
                    'stores' => $stores,
                ];
            })
            ->values()
            ->all();
    }

    private function queryByMorphotypes(array $morphotypes, string $primaryMorphotype, string $styleKey)
    {
        return FashionItem::query()
            ->with([
                'stores' => fn ($query) => $query->ordered(),
            ])
            ->active()
            ->whereIn('body_type', array_values(array_unique($morphotypes)))
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

    private function buildStylingTips(string $morphotype): array
    {
        $recommendationPayload = $this->recommendationService->forMorphotype($morphotype);
        $recommendationData = $recommendationPayload['recommendations'] ?? [];

        $tips = [];
        $styleTip = (string) session('style_tip', '');

        if ($styleTip !== '') {
            $tips[] = [
                'icon' => 'fas fa-wand-magic-sparkles',
                'tip' => $styleTip,
            ];
        }

        $focus = (string) ($recommendationData['focus'] ?? '');
        if ($focus !== '') {
            $tips[] = [
                'icon' => 'fas fa-bullseye',
                'tip' => $focus,
            ];
        }

        foreach (array_slice($recommendationData['tops'] ?? [], 0, 2) as $topTip) {
            $tips[] = [
                'icon' => 'fas fa-shirt',
                'tip' => $topTip,
            ];
        }

        foreach (array_slice($recommendationData['bottoms'] ?? [], 0, 2) as $bottomTip) {
            $tips[] = [
                'icon' => 'fas fa-shoe-prints',
                'tip' => $bottomTip,
            ];
        }

        if (empty($tips)) {
            $tips[] = [
                'icon' => 'fas fa-lightbulb',
                'tip' => 'Choose pieces that keep your silhouette balanced and comfortable.',
            ];
        }

        return array_values(array_slice($tips, 0, 4));
    }
}
