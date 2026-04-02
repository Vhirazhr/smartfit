<?php

namespace Tests\Feature;

use Tests\TestCase;

class RecommendationTest extends TestCase
{
    public function test_it_returns_classification_and_recommendations(): void
    {
        $response = $this->postJson('/api/recommendations', [
            'bust' => 130,
            'waist' => 100,
            'hip' => 110,
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.classification.morphotype', 'y_shape')
            ->assertJsonPath('data.classification.label', 'Y Shape')
            ->assertJsonPath('data.recommendation.morphotype', 'y_shape')
            ->assertJsonPath('data.recommendation.recommendations.focus', 'Soften shoulder width while adding volume below the waist.');
    }

    public function test_it_returns_undefined_recommendation_for_boundary_case(): void
    {
        $response = $this->postJson('/api/recommendations', [
            'bust' => 124,
            'waist' => 100,
            'hip' => 115,
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.classification.morphotype', 'undefined')
            ->assertJsonPath('data.recommendation.morphotype', 'undefined')
            ->assertJsonPath('data.recommendation.recommendations.focus', 'Use fit-first styling and prioritize comfort and proportion checks.');
    }

    public function test_it_validates_recommendation_input(): void
    {
        $response = $this->postJson('/api/recommendations', [
            'bust' => 0,
            'waist' => 0,
            'hip' => 0,
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['bust', 'waist', 'hip']);
    }
}