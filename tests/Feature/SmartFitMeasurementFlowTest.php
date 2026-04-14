<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SmartFitMeasurementFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_stores_measurement_and_redirects_to_result(): void
    {
        $response = $this->post(route('smartfit.calculate'), [
            'bust' => 100,
            'waist' => 70,
            'hip' => 95,
        ]);

        $response->assertRedirect(route('smartfit.result'));

        $this->assertDatabaseHas('body_measurements', [
            'bust' => 100.00,
            'waist' => 70.00,
            'hip' => 95.00,
            'morphotype' => 'hourglass',
            'morphotype_label' => 'Hourglass',
            'measurement_standard' => 'ISO 8559-1',
            'source' => 'smartfit_web_wizard',
        ]);
    }

    public function test_it_rejects_inconsistent_measurements_and_prompts_recheck(): void
    {
        $response = $this->from(route('smartfit.input'))->post(route('smartfit.calculate'), [
            'bust' => 80,
            'waist' => 120,
            'hip' => 90,
        ]);

        $response
            ->assertRedirect(route('smartfit.input'))
            ->assertSessionHasErrors(['waist']);

        $this->assertDatabaseCount('body_measurements', 0);
    }
}
