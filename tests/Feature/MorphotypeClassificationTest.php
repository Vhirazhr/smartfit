<?php

namespace Tests\Feature;

use Tests\TestCase;

class MorphotypeClassificationTest extends TestCase
{
    public function test_it_classifies_hourglass(): void
    {
        $this->assertClassification(100, 70, 95, 'hourglass', 'Hourglass');
    }

    public function test_it_classifies_rectangle(): void
    {
        $this->assertClassification(75, 70, 76, 'rectangle', 'Rectangle');
    }

    public function test_it_classifies_spoon(): void
    {
        $this->assertClassification(110, 100, 125, 'spoon', 'Spoon');
    }

    public function test_it_classifies_triangle(): void
    {
        $this->assertClassification(95, 100, 125, 'triangle', 'Triangle');
    }

    public function test_it_classifies_y_shape(): void
    {
        $this->assertClassification(130, 100, 110, 'y_shape', 'Y');
    }

    public function test_it_classifies_inverted_triangle(): void
    {
        $this->assertClassification(130, 100, 88.5, 'inverted_triangle', 'Inverted Triangle');
    }

    public function test_it_classifies_inverted_u(): void
    {
        $this->assertClassification(95, 100, 110, 'inverted_u', 'Inverted U');
    }

    public function test_it_classifies_u(): void
    {
        $this->assertClassification(110, 100, 88.5, 'u', 'U');
    }

    public function test_it_classifies_diamond(): void
    {
        $this->assertClassification(95, 100, 88.5, 'diamond', 'Diamond');
    }

    public function test_it_returns_undefined_for_threshold_boundary_values(): void
    {
        $this->assertClassification(124, 100, 115, 'undefined', 'Undefined');
        $this->assertClassification(110, 100, 120, 'undefined', 'Undefined');
        $this->assertClassification(97, 100, 121, 'undefined', 'Undefined');
        $this->assertClassification(110, 100, 89, 'undefined', 'Undefined');
        $this->assertClassification(125, 100, 120, 'undefined', 'Undefined');
    }

    public function test_it_validates_required_fields(): void
    {
        $response = $this->postJson('/api/morphotype/classify', []);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['bust', 'waist', 'hip']);
    }

    public function test_it_validates_positive_measurements(): void
    {
        $response = $this->postJson('/api/morphotype/classify', [
            'bust' => 0,
            'waist' => -1,
            'hip' => 0,
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['bust', 'waist', 'hip']);
    }

    private function assertClassification(
        float $bust,
        float $waist,
        float $hip,
        string $expectedMorphotype,
        string $expectedLabel
    ): void {
        $response = $this->postJson('/api/morphotype/classify', [
            'bust' => $bust,
            'waist' => $waist,
            'hip' => $hip,
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.morphotype', $expectedMorphotype)
            ->assertJsonPath('data.label', $expectedLabel);
    }
}
