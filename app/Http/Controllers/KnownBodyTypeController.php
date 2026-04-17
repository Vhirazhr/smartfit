<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KnownBodyTypeController extends Controller
{
    public function selectBodyType()
    {
        return view('smartfit.select-body-type');
    }
    
    public function processKnownBodyType(Request $request)
    {
        $request->validate([
            'body_type' => 'required|in:Hourglass,Rectangle,Spoon,Triangle,Inverted Triangle',
            'style_preference' => 'required|in:Casual,Formal,Bohemian,Classic,Sporty',
        ]);
        
        $bodyType = $request->body_type;
        $stylePreference = $request->style_preference;
        $colorTone = $request->color_tone ?? null;
        
        $recommendations = $this->getRecommendationsByBodyType($bodyType, $stylePreference, $colorTone);
        
        session([
            'body_type' => $bodyType,
            'style_preference' => $stylePreference,
            'color_tone' => $colorTone,
            'description' => $recommendations['description'],
            'recommendations' => $recommendations['recommendations'],
            'avoid' => $recommendations['avoid'],
            'style_tip' => $recommendations['style_tip'] ?? '',
            'color_tip' => $recommendations['color_tip'] ?? '',
            'source' => 'known'
        ]);
        
        return redirect()->route('known.result');
    }
    
    public function result()
    {
        if (!session('body_type')) {
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
            'source' => session('source')
        ]);
    }
    
    private function getRecommendationsByBodyType($bodyType, $stylePreference = null, $colorTone = null)
    {
        $baseData = [
            'Hourglass' => [
                'description' => 'Balanced bust and hips with a clearly defined, narrow waist.',
                'recommendations' => [
                    'Wrap dresses and belted styles',
                    'Fitted tops that define the waist',
                    'V-neck and sweetheart necklines',
                    'High-waisted pants and pencil skirts'
                ],
                'avoid' => ['Boxy, shapeless clothing', 'Oversized silhouettes']
            ],
            'Spoon' => [
                'description' => 'Hips wider than bust with a defined waist.',
                'recommendations' => [
                    'A-line skirts and wide-leg pants',
                    'Statement necklaces and bold tops',
                    'Dark, plain colors on bottom',
                    'Boat neck and off-shoulder tops'
                ],
                'avoid' => ['Tight skirts that cling to hips', 'Horizontal stripes on hips']
            ],
            'Rectangle' => [
                'description' => 'Bust and hip are fairly equal with minimal waist definition.',
                'recommendations' => [
                    'Peplum tops and belted dresses',
                    'Layered accessories to create curves',
                    'High-waisted skirts and pants',
                    'Wrap styles to define waist'
                ],
                'avoid' => ['Oversized, shapeless cuts', 'Monochromatic looks']
            ],
            'Triangle' => [
                'description' => 'Hips wider than shoulders with a defined waist.',
                'recommendations' => [
                    'Bright colors and patterns on top',
                    'Dark, solid colors on bottom',
                    'Boat neck and off-shoulder tops',
                    'A-line skirts that flow away from hips'
                ],
                'avoid' => ['Tight skirts that emphasize hips', 'Horizontal stripes on lower body']
            ],
            'Inverted Triangle' => [
                'description' => 'Shoulders/bust wider than hips with minimal waist definition.',
                'recommendations' => [
                    'V-neck and scoop neck tops',
                    'A-line skirts and flared pants',
                    'Dark colors on top, lighter on bottom',
                    'Simple tops, bold bottoms'
                ],
                'avoid' => ['Puff sleeves and shoulder pads', 'Halter necks']
            ]
        ];
        
        $data = $baseData[$bodyType] ?? $baseData['Rectangle'];
        
        if ($stylePreference) {
            $styleTips = [
                'Casual' => 'Pair with sneakers, denim jacket, and minimal accessories.',
                'Formal' => 'Complete with structured blazer and elegant handbag.',
                'Bohemian' => 'Add layered necklaces and fringe bag.',
                'Classic' => 'Timeless pieces with pearl accessories.',
                'Sporty' => 'Finish with white sneakers and baseball cap.'
            ];
            $data['style_tip'] = $styleTips[$stylePreference] ?? '';
        }
        
        if ($colorTone) {
            $colorTips = [
                'Light' => 'Pastels, cream, and soft neutrals will brighten your look.',
                'Bright' => 'Bold reds, royal blue, and emerald green make a statement.',
                'Neutral' => 'Black, navy, beige, and gray for versatile elegance.',
                'Dark' => 'Deep burgundy and charcoal for sophisticated edge.',
                'Earth' => 'Olive green, terracotta, and rust for natural warmth.'
            ];
            $data['color_tip'] = $colorTips[$colorTone] ?? '';
        }
        
        return $data;
    }
}