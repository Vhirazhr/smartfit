# SMARTFIT System

SmartFIT is a digital expert system designed to identify user body morphotypes and provide personalized clothing recommendations based on anthropometric data analysis.

The system transforms subjective visual body assessment into a quantitative classification model by analyzing the proportional relationships between key body measurements: bust, waist, and hip.

Instead of relying on visual judgment, SmartFIT applies a ratio-based evaluation method, where the waist acts as the central reference point for determining upper and lower body proportions.

## Project Goals
1. Identify body morphotype using measurable ratios
- Calculate proportional relationships between bust, waist, and hip
- Normalize different body sizes into comparable data
2. Reduce subjectivity in classification
- Replace visual estimation with rule-based computation
- Ensure consistent classification results
3. Provide personalized clothing recommendations
- Match clothing styles with body proportions
- Improve visual balance and fit
4. Support fashion personalization
- Enable tailored recommendations for each user
- Provide a scalable solution for fashion platforms
5. Deliver avatar-based visualization
- Display results using avatar (2D / 3D)
- Help users understand styling outcomes

## Technology Stack
### Backend
- Laravel 10 (REST API)
- MySQL
- Service Layer Architecture

### Frontend
- Vue 3 (Composition API)
- Pinia
- Tailwind CSS

### Visualization
- 2D Avatar (SVG)
- 3D Avatar (Three.js + GLB)
- Blender + Mixamo

## Core Scientific Model (Morphotype Calculation)
### Fundamental Concept
The body is divided into three main measurements:

- Bust (B)
- Waist (W)
- Hip (H)

The waist is used as the baseline reference, and two key ratios are calculated:

- b = Bust / Waist
- h = Hip / Waist

These ratios represent:

- b (upper body proportion)
- h (lower body proportion)

## Why Ratio-Based Classification?

Human bodies do not follow exact proportions.
Therefore, instead of using exact equality (e.g., b = 1), the method defines tolerance ranges around 1.

Concept:
- If ratio ≈ 1 → considered “balanced”
- If ratio > 1 → considered “larger than waist”
- If ratio < 1 → considered “smaller than waist”

To handle real-world variation, boundaries are introduced:

- Bust ratio threshold:
Lower bound ≈ 0.97
Upper bound ≈ 1.24
- Hip ratio threshold:
Lower bound ≈ 0.89
Upper bound ≈ 1.20

These thresholds are derived from:

- Mean values of population data
- Standard deviation
- Distribution analysis

## Morphotype Classification Rules

Using the ratio ranges, body shapes are classified as follows:

1. Hourglass
- Bust significantly larger than waist
- Hip significantly larger than waist
Condition:
b > 1.24 AND h > 1.20

2. Rectangle
- Bust ≈ Waist
- Hip ≈ Waist
Condition:
0.97 < b < 1.24 AND 0.89 < h < 1.20

3. Spoon
- Bust ≈ Waist
- Hip larger than waist
Condition:
0.97 < b < 1.24 AND h > 1.20

4. Triangle
- Bust smaller than waist
- Hip larger than waist
Condition:
b < 0.97 AND h > 1.20

5. Y Shape
- Bust larger than waist
- Hip ≈ Waist
Condition:
b > 1.24 AND 0.89 < h < 1.20

6. Inverted U
- Bust smaller than waist
- Hip ≈ Waist
Condition:
b < 0.97 AND 0.89 < h < 1.20

## Key Insight from the Model
1. Single classification output
- Each user only belongs to one morphotype
2. No ambiguity
- Threshold ranges prevent overlap
3. Statistically grounded
- Based on real population distribution
4. High consistency
- Produces stable results across different users

## Implementation Example (Laravel Service)
{
    public function classify($bust, $waist, $hip)
    {
        $b = $bust / $waist;
        $h = $hip / $waist;

        if ($b > 1.24 && $h > 1.20) {
            return 'hourglass';
        }

        if ($b > 1.24 && $h > 0.89 && $h < 1.20) {
            return 'Y';
        }

        if ($b < 0.97 && $h > 1.20) {
            return 'triangle';
        }

        if ($b > 0.97 && $b < 1.24 && $h > 1.20) {
            return 'spoon';
        }

        if ($b > 0.97 && $b < 1.24 && $h > 0.89 && $h < 1.20) {
            return 'rectangle';
        }

        if ($b < 0.97 && $h > 0.89 && $h < 1.20) {
            return 'inverted_u';
        }

        return 'undefined';
    }
}

## Core Features
1. Anthropometric Input
- Bust
- Waist
- Hip

2. Morphotype Engine
- Ratio calculation
- Classification using thresholds

3. Recommendation Engine
- Map morphotype → clothing style

4. Avatar System
- 2D (primary)
- 3D (advanced)

5. Visualization Preview
- Show outfit on avatar

## Working Guideline
1. Build structured architecture
2. Separate business logic (Service Layer)
3. Use descriptive naming
4. When you found somethings useful, write it into /docs folder for future reference
5. Validate ratio thresholds before implementation

## Technical Specification
1. PSR-12 standard
2. REST API
3. Laravel sactum
4. Vue Composition API
5. Pinia for state

## Development Phases
### Phase 1
- Ratio calculation
- Morphotype classification
### Phase 2
- Recommendation system
### Phase 3
- 2D avatar
### Phase 4
- 3D avatar
### Key Adventages
- Objective and measurable
- Reduces human bias
- Based on statistical model
- Suitable for real-world fashion systems