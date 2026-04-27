<?php

namespace App\Http\Controllers;

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

        return view('smartfit.result-known', [
            'bodyType' => session('body_type'),
            'stylePreference' => session('style_preference'),
            'colorTone' => session('color_tone'),
            'description' => session('description'),
            'recommendations' => session('recommendations'),
            'avoid' => session('avoid'),
            'styleTip' => session('style_tip'),
            'colorTip' => session('color_tip'),
            'source' => session('source'),
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
}
