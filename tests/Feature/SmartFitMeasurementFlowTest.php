<?php

namespace Tests\Feature;

use App\Models\FashionCategory;
use App\Models\FashionItem;
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

    public function test_it_updates_recommendation_after_style_preference_submission(): void
    {
        $response = $this
            ->withSession([
                'body_type' => 'Hourglass',
                'description' => 'Initial recommendation focus',
                'recommendation_focus' => 'Initial recommendation focus',
                'recommendation_tops' => ['Wrap tops'],
                'recommendation_bottoms' => ['Pencil skirts'],
                'avoid' => ['Boxy oversized tops without waist shape'],
                'source' => 'calculated',
            ])
            ->post(route('smartfit.get.recommendation'), [
                'style_preference' => 'Formal',
            ]);

        $response
            ->assertRedirect(route('smartfit.recommendation'))
            ->assertSessionHas('style_preference', 'Formal')
            ->assertSessionHas('recommendation_focus', 'Refine the silhouette with structured tailoring and polished details.')
            ->assertSessionHas('style_tip', 'Use a structured blazer and refined footwear to keep proportions polished.')
            ->assertSessionHas('recommendation_updated');

        $this->assertContains('Structured blazers', session('recommendation_tops', []));
        $this->assertContains('Tailored trousers', session('recommendation_bottoms', []));
    }

    public function test_recommendation_page_shows_personalized_recommendation_showcase_when_style_is_selected(): void
    {
        $category = FashionCategory::query()->create([
            'name' => 'Casual',
            'slug' => 'casual',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        FashionItem::query()->create([
            'fashion_category_id' => $category->id,
            'title' => 'Database Casual Hourglass Look',
            'description' => 'Recommendation source from database record.',
            'body_type' => 'hourglass',
            'style_preference' => 'casual',
            'color_tone' => 'light',
            'image_source' => 'url',
            'image_url' => 'https://example.com/hourglass-look.jpg',
            'purchase_link' => 'https://example.com/hourglass-buy',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $response = $this
            ->withSession([
                'body_type' => 'Hourglass',
                'style_preference' => 'Casual',
                'description' => 'Focus description',
                'recommendation_focus' => 'Focus description',
                'recommendation_tops' => ['Wrap tops'],
                'recommendation_bottoms' => ['Pencil skirts'],
                'avoid' => ['Boxy oversized tops without waist shape'],
                'source' => 'calculated',
            ])
            ->get(route('smartfit.recommendation'));

        $response
            ->assertOk()
            ->assertSee('Your Personalized Recommendations')
            ->assertSee('Database Casual Hourglass Look')
            ->assertSee('Styling Tips for Hourglass');
    }
}
