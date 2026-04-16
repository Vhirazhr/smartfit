<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SmartFitController extends Controller
{
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
    public function calculate(Request $request)
    {
        $request->validate([
            'bust' => 'required|numeric|min:50|max:200',
            'waist' => 'required|numeric|min:40|max:200',
            'hip' => 'required|numeric|min:50|max:200',
        ]);
        
        $bust = $request->bust;
        $waist = $request->waist;
        $hip = $request->hip;
        
        $bwRatio = round($bust / $waist, 2);
        $hwRatio = round($hip / $waist, 2);
        
        // Forward Chaining Classification
        $bodyType = $this->classifyBodyType($bwRatio, $hwRatio);
        $recommendations = $this->getRecommendationsByBodyType($bodyType);
        
        session([
            'body_type' => $bodyType,
            'description' => $recommendations['description'],
            'recommendations' => $recommendations['recommendations'],
            'avoid' => $recommendations['avoid'],
            'bw_ratio' => $bwRatio,
            'hw_ratio' => $hwRatio,
            'bust' => $bust,
            'waist' => $waist,
            'hip' => $hip,
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
            'bodyType' => session('body_type'),
            'description' => session('description'),
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
            'source' => session('source')
        ]);
    }
    
    /**
     * Klasifikasi body type berdasarkan rasio B/W dan H/W
     */
    private function classifyBodyType($bwRatio, $hwRatio)
    {
        if ($bwRatio > 1.24 && $hwRatio > 1.20) {
            return 'Hourglass';
        } elseif ($bwRatio >= 0.97 && $bwRatio <= 1.24 && $hwRatio > 1.20) {
            return 'Spoon';
        } elseif ($bwRatio >= 0.97 && $bwRatio <= 1.24 && $hwRatio >= 0.89 && $hwRatio <= 1.20) {
            return 'Rectangle';
        } elseif ($bwRatio < 0.97 && $hwRatio > 1.20) {
            return 'Triangle';
        } elseif ($bwRatio > 1.24 && $hwRatio < 0.89) {
            return 'Inverted Triangle';
        } else {
            return 'Rectangle';
        }
    }
    
    /**
     * Data rekomendasi berdasarkan body type (dengan tambahan style & color)
     */
    private function getRecommendationsByBodyType($bodyType, $stylePreference = null, $colorTone = null)
    {
        // Base data berdasarkan body type
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
                'description' => 'Hips wider than bust with a defined waist and "shelf" at hips.',
                'recommendations' => [
                    'A-line skirts and wide-leg pants',
                    'Statement necklaces and bold tops on upper body',
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
                'avoid' => ['Oversized, shapeless cuts', 'Monochromatic looks without waist definition']
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
                'avoid' => ['Puff sleeves and shoulder pads', 'Halter necks that emphasize shoulders']
            ]
        ];
        
        $data = $baseData[$bodyType] ?? $baseData['Rectangle'];
        
        // Tambahan style tip jika ada
        if ($stylePreference) {
            $styleTips = [
                'Casual' => 'Pair with sneakers, denim jacket, and minimal accessories for everyday comfort.',
                'Formal' => 'Complete with structured blazer, classic pumps, and elegant handbag.',
                'Bohemian' => 'Add layered necklaces, floppy hat, and fringe bag for free-spirited vibe.',
                'Classic' => 'Timeless pieces with pearl accessories and neutral pumps.',
                'Sporty' => 'Finish with white sneakers, baseball cap, and sporty backpack.'
            ];
            $data['style_tip'] = $styleTips[$stylePreference] ?? '';
        }
        
        // Tambahan color tip jika ada
        if ($colorTone) {
            $colorTips = [
                'Light' => 'Pastels, cream, white, and soft neutrals will brighten your look.',
                'Bright' => 'Bold reds, royal blue, emerald green, and fuchsia make a statement.',
                'Neutral' => 'Black, navy, beige, gray, and taupe for versatile elegance.',
                'Dark' => 'Deep burgundy, forest green, charcoal, and navy for sophisticated edge.',
                'Earth' => 'Olive green, terracotta, mustard, and rust brown for natural warmth.'
            ];
            $data['color_tip'] = $colorTips[$colorTone] ?? '';
        }
        
        return $data;
    }
}