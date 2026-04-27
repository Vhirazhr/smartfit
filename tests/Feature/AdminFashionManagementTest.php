<?php

namespace Tests\Feature;

use App\Models\FashionCategory;
use App\Models\FashionItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminFashionManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_update_and_delete_fashion_category(): void
    {
        $this->withSession(['admin' => true])
            ->post(route('admin.fashion-categories.store'), [
                'name' => 'Formal',
                'slug' => 'formal',
                'description' => 'Formal style category',
                'is_active' => '1',
                'sort_order' => 2,
            ])
            ->assertRedirect(route('admin.fashion-categories.index'));

        $category = FashionCategory::query()->first();
        $this->assertNotNull($category);

        $this->withSession(['admin' => true])
            ->post(route('admin.fashion-categories.update', $category->id), [
                'name' => 'Formal Wear',
                'slug' => 'formal-wear',
                'description' => 'Updated category',
                'is_active' => '1',
                'sort_order' => 1,
            ])
            ->assertRedirect(route('admin.fashion-categories.index'));

        $this->assertDatabaseHas('fashion_categories', [
            'id' => $category->id,
            'name' => 'Formal Wear',
            'slug' => 'formal-wear',
            'sort_order' => 1,
        ]);

        $this->withSession(['admin' => true])
            ->post(route('admin.fashion-categories.delete', $category->id))
            ->assertRedirect();

        $this->assertDatabaseMissing('fashion_categories', [
            'id' => $category->id,
        ]);
    }

    public function test_admin_can_store_update_and_delete_fashion_item_with_upload_lifecycle(): void
    {
        Storage::fake('public');

        $category = FashionCategory::query()->create([
            'name' => 'Casual',
            'slug' => 'casual',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $file = UploadedFile::fake()->image('look.jpg');

        $this->withSession(['admin' => true])
            ->post(route('admin.fashion-items.store'), [
                'fashion_category_id' => $category->id,
                'title' => 'Relaxed Blazer Set',
                'description' => 'Comfortable and structured.',
                'body_type' => 'rectangle',
                'image_source' => 'upload',
                'image_file' => $file,
                'purchase_link' => 'https://example.com/product-1',
                'is_active' => '1',
                'sort_order' => 1,
            ])
            ->assertRedirect(route('admin.fashion-items.index'));

        $item = FashionItem::query()->first();
        $this->assertNotNull($item);
        $this->assertNotNull($item->image_path);
        $this->assertTrue(Storage::disk('public')->exists((string) $item->image_path));
        $oldPath = (string) $item->image_path;

        $this->withSession(['admin' => true])
            ->post(route('admin.fashion-items.update', $item->id), [
                'fashion_category_id' => $category->id,
                'title' => 'Relaxed Blazer Set Updated',
                'description' => 'Updated description.',
                'body_type' => 'rectangle',
                'image_source' => 'url',
                'image_url' => 'https://cdn.example.com/look-1.jpg',
                'purchase_link' => 'https://example.com/product-2',
                'is_active' => '1',
                'sort_order' => 2,
                'keep_existing_image' => '0',
            ])
            ->assertRedirect(route('admin.fashion-items.index'));

        $item->refresh();

        $this->assertSame('url', $item->image_source);
        $this->assertNull($item->image_path);
        $this->assertSame('https://cdn.example.com/look-1.jpg', $item->image_url);
        $this->assertFalse(Storage::disk('public')->exists($oldPath));

        $this->withSession(['admin' => true])
            ->post(route('admin.fashion-items.delete', $item->id))
            ->assertRedirect();

        $this->assertDatabaseMissing('fashion_items', [
            'id' => $item->id,
        ]);
    }

    public function test_gallery_page_renders_active_items_from_database(): void
    {
        $category = FashionCategory::query()->create([
            'name' => 'Office',
            'slug' => 'office',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        FashionItem::query()->create([
            'fashion_category_id' => $category->id,
            'title' => 'Smart Office Blazer',
            'description' => 'Clean office styling.',
            'body_type' => 'hourglass',
            'image_source' => 'url',
            'image_url' => 'https://example.com/office-look.jpg',
            'purchase_link' => 'https://example.com/office-product',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        FashionItem::query()->create([
            'fashion_category_id' => $category->id,
            'title' => 'Hidden Draft Item',
            'description' => 'Must not appear publicly.',
            'body_type' => 'hourglass',
            'image_source' => 'url',
            'image_url' => 'https://example.com/hidden.jpg',
            'is_active' => false,
            'sort_order' => 1,
        ]);

        $this->get(route('gallery'))
            ->assertOk()
            ->assertSee('Smart Office Blazer')
            ->assertSee('https://example.com/office-product')
            ->assertDontSee('Hidden Draft Item');
    }
}
