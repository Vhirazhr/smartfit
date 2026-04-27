<?php

namespace Tests\Feature;

use App\Models\FashionCategory;
use App\Models\FashionItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminDashboardKnownFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_can_store_fashion_item_with_multiple_stores(): void
    {
        Storage::fake('public');

        $response = $this->withSession(['admin' => true])
            ->post(route('admin.dashboard.fashion-items.store'), [
                'title' => 'Structured Workwear Set',
                'body_type' => 'hourglass',
                'style_preference' => 'formal',
                'color_tone' => 'neutral',
                'description' => 'Formal set with strong silhouette lines.',
                'image_source' => 'upload',
                'image_file' => UploadedFile::fake()->image('workwear.jpg'),
                'stores_payload' => json_encode([
                    ['name' => 'Zalora', 'link' => 'https://example.com/zalora-item'],
                    ['name' => 'Shopee', 'link' => 'https://example.com/shopee-item'],
                ], JSON_THROW_ON_ERROR),
            ]);

        $response->assertRedirect(route('admin.dashboard'));

        $item = FashionItem::query()->with('stores')->first();

        $this->assertNotNull($item);
        $this->assertSame('formal', $item->style_preference);
        $this->assertSame('neutral', $item->color_tone);
        $this->assertSame('https://example.com/zalora-item', $item->purchase_link);
        $this->assertCount(2, $item->stores);
        $this->assertNotNull($item->image_path);
        $this->assertTrue(Storage::disk('public')->exists((string) $item->image_path));

        $this->assertDatabaseHas('fashion_item_stores', [
            'fashion_item_id' => $item->id,
            'store_name' => 'Zalora',
        ]);

        $this->assertDatabaseHas('fashion_item_stores', [
            'fashion_item_id' => $item->id,
            'store_name' => 'Shopee',
        ]);
    }

    public function test_admin_dashboard_can_store_fashion_item_with_image_url(): void
    {
        $response = $this->withSession(['admin' => true])
            ->post(route('admin.dashboard.fashion-items.store'), [
                'title' => 'Summer Linen Top',
                'body_type' => 'hourglass',
                'style_preference' => 'casual',
                'color_tone' => 'light',
                'description' => 'Lightweight top from external image URL.',
                'image_source' => 'url',
                'image_url' => 'https://images.example.com/look.jpg',
                'stores_payload' => json_encode([
                    ['name' => 'Tokopedia', 'link' => 'https://example.com/tokopedia-item'],
                ], JSON_THROW_ON_ERROR),
            ]);

        $response->assertRedirect(route('admin.dashboard'));

        $item = FashionItem::query()->latest('id')->first();

        $this->assertNotNull($item);
        $this->assertSame('url', $item->image_source);
        $this->assertSame('https://images.example.com/look.jpg', $item->image_url);
        $this->assertNull($item->image_path);
    }

    public function test_known_result_uses_database_fashion_items(): void
    {
        $category = FashionCategory::query()->create([
            'name' => 'Casual',
            'slug' => 'casual',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $item = FashionItem::query()->create([
            'fashion_category_id' => $category->id,
            'title' => 'Database Recommendation Dress',
            'description' => 'Loaded dynamically from fashion_items data.',
            'body_type' => 'hourglass',
            'style_preference' => 'casual',
            'color_tone' => 'light',
            'image_source' => 'url',
            'image_url' => 'https://example.com/db-look.jpg',
            'purchase_link' => 'https://example.com/db-look-buy',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $item->stores()->create([
            'store_name' => 'Zalora',
            'store_link' => 'https://example.com/db-look-buy',
            'sort_order' => 0,
        ]);

        $response = $this->withSession([
            'body_type' => 'Hourglass',
            'morphotype' => 'hourglass',
            'style_preference' => 'Casual',
            'source' => 'known',
            'style_tip' => 'Keep the fit balanced and comfortable.',
        ])->get(route('known.result'));

        $response
            ->assertOk()
            ->assertSee('Database Recommendation Dress')
            ->assertSee('Zalora')
            ->assertSee('https://example.com/db-look.jpg');
    }
}
