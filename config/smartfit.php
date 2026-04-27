<?php

return [
    'measurement_ranges' => [
        'bust' => [
            'min' => 60,
            'max' => 170,
        ],
        'waist' => [
            'min' => 50,
            'max' => 160,
        ],
        'hip' => [
            'min' => 70,
            'max' => 180,
        ],
    ],

    'consistency_rules' => [
        'waist_to_hip_max_factor' => 1.14,
        'waist_to_bust_max_factor' => 1.15,
        'max_bust_hip_gap_cm' => 70,
    ],

    'thresholds' => [
        'bust' => [
            'lower' => 0.97,
            'upper' => 1.24,
        ],
        'hip' => [
            'lower' => 0.89,
            'upper' => 1.20,
        ],
    ],

    'body_types' => [
        'hourglass',
        'y_shape',
        'inverted_triangle',
        'spoon',
        'rectangle',
        'u',
        'triangle',
        'inverted_u',
        'diamond',
    ],

    'manual_body_types' => [
        'Hourglass' => 'hourglass',
        'Y' => 'y_shape',
        'Inverted Triangle' => 'inverted_triangle',
        'Spoon' => 'spoon',
        'Rectangle' => 'rectangle',
        'U' => 'u',
        'Triangle' => 'triangle',
        'Inverted U' => 'inverted_u',
        'Diamond' => 'diamond',
    ],

    'labels' => [
        'hourglass' => 'Hourglass',
        'y_shape' => 'Y',
        'inverted_triangle' => 'Inverted Triangle',
        'spoon' => 'Spoon',
        'rectangle' => 'Rectangle',
        'u' => 'U',
        'triangle' => 'Triangle',
        'inverted_u' => 'Inverted U',
        'diamond' => 'Diamond',
        'undefined' => 'Undefined',
    ],

    'descriptions' => [
        'hourglass' => 'Balanced bust and hips with a clearly defined waistline.',
        'y_shape' => 'Upper body is visually broader, while hips appear more compact.',
        'inverted_triangle' => 'Bust or shoulders are significantly broader than hips.',
        'spoon' => 'Hips are emphasized with a defined waist and moderate bust.',
        'rectangle' => 'Bust and hips are close in proportion with subtle waist definition.',
        'u' => 'Bust and hips are moderate while the waist appears less defined and fuller.',
        'triangle' => 'Hips are broader than bust with a visibly narrower upper body.',
        'inverted_u' => 'Upper and lower body are moderate with fuller waist emphasis.',
        'diamond' => 'Midsection appears fuller while bust and hips are comparatively narrower.',
        'undefined' => 'The ratios are on boundary values and need re-check for a stable category.',
    ],

    'recommendations' => [
        'hourglass' => [
            'focus' => 'Maintain natural balance while emphasizing waist definition.',
            'tops' => ['Wrap tops', 'Fitted blouses', 'Sweetheart necklines'],
            'bottoms' => ['High-waist trousers', 'Pencil skirts', 'Tailored jeans'],
            'avoid' => ['Boxy oversized tops without waist shape'],
        ],
        'y_shape' => [
            'focus' => 'Soften upper width and add visual structure in the lower body.',
            'tops' => ['V-necklines', 'Soft drape fabrics', 'Minimal shoulder structure'],
            'bottoms' => ['Pleated skirts', 'Wide-leg pants', 'Patterned bottoms'],
            'avoid' => ['Puff shoulders and heavy shoulder pads'],
        ],
        'inverted_triangle' => [
            'focus' => 'Balance broad upper body with volume and detail below the waist.',
            'tops' => ['Clean V-neck tops', 'Raglan sleeves', 'Simple structured blazers'],
            'bottoms' => ['A-line skirts', 'Flared pants', 'Textured bottoms'],
            'avoid' => ['Boat necklines and bulky shoulder details'],
        ],
        'spoon' => [
            'focus' => 'Balance lower-body emphasis by drawing attention upward.',
            'tops' => ['Structured shoulders', 'Boat neck tops', 'Statement sleeves'],
            'bottoms' => ['A-line skirts', 'Dark straight jeans', 'Mid-rise tailored pants'],
            'avoid' => ['Heavy embellishment around hips'],
        ],
        'rectangle' => [
            'focus' => 'Create contour and visual waist structure through layering and shaping.',
            'tops' => ['Peplum tops', 'Layered blouses', 'Ruffled necklines'],
            'bottoms' => ['Pleated skirts', 'Paperbag pants', 'Wide-leg trousers'],
            'avoid' => ['Very straight cuts from shoulder to hem'],
        ],
        'u' => [
            'focus' => 'Create vertical flow while gently defining the waist and bust line.',
            'tops' => ['Open necklines', 'Soft structured tunics', 'Single-button outerwear'],
            'bottoms' => ['Straight trousers', 'Bootcut jeans', 'Fluid midi skirts'],
            'avoid' => ['Overly clingy pieces around midsection'],
        ],
        'triangle' => [
            'focus' => 'Enhance upper body presence and keep lower silhouettes clean.',
            'tops' => ['Bright tops', 'Horizontal shoulder details', 'Layered jackets'],
            'bottoms' => ['Straight-leg pants', 'Dark A-line skirts', 'Clean-cut denim'],
            'avoid' => ['Busy patterns and light tones on lower body'],
        ],
        'inverted_u' => [
            'focus' => 'Introduce structure and directional lines for better top-bottom balance.',
            'tops' => ['Structured blazers', 'Angular necklines', 'Layered tops'],
            'bottoms' => ['Straight pants', 'Subtle flare skirts', 'Textured trousers'],
            'avoid' => ['Overly clingy fabrics around midsection'],
        ],
        'diamond' => [
            'focus' => 'Create cleaner waist framing and visual width on shoulders and hips.',
            'tops' => ['Shoulder-detail tops', 'Open jackets', 'V-neck tunics'],
            'bottoms' => ['A-line skirts', 'Straight-leg pants', 'Mid-rise denim'],
            'avoid' => ['Tight one-layer outfits that emphasize the midsection'],
        ],
        'undefined' => [
            'focus' => 'Re-check measurement consistency and apply fit-first styling.',
            'tops' => ['Semi-fitted basics', 'Adjustable wrap tops'],
            'bottoms' => ['Straight trousers', 'A-line skirts'],
            'avoid' => ['One-size assumptions without fitting review'],
        ],
    ],
];
