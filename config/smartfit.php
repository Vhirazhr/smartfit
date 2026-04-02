<?php

return [
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

    'labels' => [
        'hourglass' => 'Hourglass',
        'rectangle' => 'Rectangle',
        'spoon' => 'Spoon',
        'triangle' => 'Triangle',
        'y_shape' => 'Y Shape',
        'inverted_u' => 'Inverted U',
        'undefined' => 'Undefined',
    ],

    'recommendations' => [
        'hourglass' => [
            'focus' => 'Highlight balanced curves while preserving waist definition.',
            'tops' => ['Wrap tops', 'Fitted blouses', 'Sweetheart necklines'],
            'bottoms' => ['High-waist trousers', 'Pencil skirts', 'Tailored jeans'],
            'avoid' => ['Boxy oversized tops without waist shape'],
        ],
        'rectangle' => [
            'focus' => 'Create visual waist shape and add contour.',
            'tops' => ['Peplum tops', 'Layered blouses', 'Ruffled necklines'],
            'bottoms' => ['Pleated skirts', 'Paperbag pants', 'Wide-leg trousers'],
            'avoid' => ['Very straight cuts from shoulder to hem'],
        ],
        'spoon' => [
            'focus' => 'Balance lower-body emphasis with upper-body volume.',
            'tops' => ['Structured shoulders', 'Boat neck tops', 'Statement sleeves'],
            'bottoms' => ['A-line skirts', 'Dark straight jeans', 'Mid-rise tailored pants'],
            'avoid' => ['Heavy embellishment around hips'],
        ],
        'triangle' => [
            'focus' => 'Add width to upper body and simplify lower silhouettes.',
            'tops' => ['Bright tops', 'Horizontal details on shoulders', 'Layered jackets'],
            'bottoms' => ['Straight-leg pants', 'Dark A-line skirts', 'Clean-cut denim'],
            'avoid' => ['Skin-tight light-colored bottoms with busy patterns'],
        ],
        'y_shape' => [
            'focus' => 'Soften shoulder width while adding volume below the waist.',
            'tops' => ['V-necklines', 'Minimal shoulder structure', 'Soft drape fabrics'],
            'bottoms' => ['Pleated skirts', 'Wide-leg pants', 'Printed trousers'],
            'avoid' => ['Shoulder pads and puff shoulders'],
        ],
        'inverted_u' => [
            'focus' => 'Introduce structure and proportional balance with cleaner lines.',
            'tops' => ['Structured blazers', 'Angular necklines', 'Layered tops'],
            'bottoms' => ['Straight pants', 'Subtle flare skirts', 'Textured trousers'],
            'avoid' => ['Overly clingy fabrics around midsection'],
        ],
        'undefined' => [
            'focus' => 'Use fit-first styling and prioritize comfort and proportion checks.',
            'tops' => ['Semi-fitted basics', 'Adjustable wrap tops'],
            'bottoms' => ['Straight trousers', 'A-line skirts'],
            'avoid' => ['One-size assumptions without fitting review'],
        ],
    ],
];