<?php

namespace Database\Seeders;

use App\Models\FashionCategory;
use App\Models\FashionItem;
use Illuminate\Database\Seeder;

class FashionGallerySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Formal',
                'slug' => 'formal',
                'description' => 'Structured outfits for meetings, events, and polished occasions.',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Casual',
                'slug' => 'casual',
                'description' => 'Everyday outfits with comfort-first proportions.',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Office',
                'slug' => 'office',
                'description' => 'Professional daily workwear with balanced silhouettes.',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Party',
                'slug' => 'party',
                'description' => 'Statement outfits for social and evening events.',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Sporty',
                'slug' => 'sporty',
                'description' => 'Activewear-inspired looks with practical movement.',
                'is_active' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($categories as $category) {
            FashionCategory::query()->updateOrCreate(
                ['slug' => $category['slug']],
                [
                    'name' => $category['name'],
                    'description' => $category['description'],
                    'is_active' => $category['is_active'],
                    'sort_order' => $category['sort_order'],
                ]
            );
        }

        $categoryIdBySlug = FashionCategory::query()->pluck('id', 'slug');

        $items = [
            [
                'title' => 'Hourglass Tailored Wrap Dress',
                'body_type' => 'hourglass',
                'category_slug' => 'formal',
                'description' => 'Waist-defining wrap silhouette with clean structured lines.',
                'image_url' => 'https://placehold.co/720x960/F2EDE7/1B1B1B?text=Hourglass+Formal',
                'purchase_link' => 'https://example.com/hourglass-formal-wrap-dress',
                'sort_order' => 1,
            ],
            [
                'title' => 'Y Shape Pleated Balance Set',
                'body_type' => 'y_shape',
                'category_slug' => 'office',
                'description' => 'Soft upper drape and volume-focused lower structure.',
                'image_url' => 'https://placehold.co/720x960/EEE5DC/1B1B1B?text=Y+Shape+Office',
                'purchase_link' => 'https://example.com/y-shape-pleated-balance-set',
                'sort_order' => 2,
            ],
            [
                'title' => 'Inverted Triangle A-Line Night Look',
                'body_type' => 'inverted_triangle',
                'category_slug' => 'party',
                'description' => 'Minimal top profile with bold A-line lower silhouette.',
                'image_url' => 'https://placehold.co/720x960/E9E4DE/1B1B1B?text=Inverted+Triangle+Party',
                'purchase_link' => 'https://example.com/inverted-triangle-a-line-night-look',
                'sort_order' => 3,
            ],
            [
                'title' => 'Spoon Shoulder Emphasis Blouse Set',
                'body_type' => 'spoon',
                'category_slug' => 'casual',
                'description' => 'Upper-body emphasis with clean dark lower combination.',
                'image_url' => 'https://placehold.co/720x960/F3EEE9/1B1B1B?text=Spoon+Casual',
                'purchase_link' => 'https://example.com/spoon-shoulder-emphasis-set',
                'sort_order' => 4,
            ],
            [
                'title' => 'Rectangle Contour Layer Outfit',
                'body_type' => 'rectangle',
                'category_slug' => 'formal',
                'description' => 'Layered contour strategy with waist-shaping accents.',
                'image_url' => 'https://placehold.co/720x960/ECE7E2/1B1B1B?text=Rectangle+Formal',
                'purchase_link' => 'https://example.com/rectangle-contour-layer-outfit',
                'sort_order' => 5,
            ],
            [
                'title' => 'U Shape Vertical Flow Ensemble',
                'body_type' => 'u',
                'category_slug' => 'office',
                'description' => 'Vertical flow lines with gentle waist framing.',
                'image_url' => 'https://placehold.co/720x960/F0EAE4/1B1B1B?text=U+Shape+Office',
                'purchase_link' => 'https://example.com/u-shape-vertical-flow-ensemble',
                'sort_order' => 6,
            ],
            [
                'title' => 'Triangle Upper Accent Suit',
                'body_type' => 'triangle',
                'category_slug' => 'formal',
                'description' => 'Upper accent details with clean lower-body tailoring.',
                'image_url' => 'https://placehold.co/720x960/EEE7E1/1B1B1B?text=Triangle+Formal',
                'purchase_link' => 'https://example.com/triangle-upper-accent-suit',
                'sort_order' => 7,
            ],
            [
                'title' => 'Inverted U Structured Casual Set',
                'body_type' => 'inverted_u',
                'category_slug' => 'casual',
                'description' => 'Structured layering with controlled silhouette balance.',
                'image_url' => 'https://placehold.co/720x960/F1E9E1/1B1B1B?text=Inverted+U+Casual',
                'purchase_link' => 'https://example.com/inverted-u-structured-casual-set',
                'sort_order' => 8,
            ],
            [
                'title' => 'Diamond Frame Sport Layer',
                'body_type' => 'diamond',
                'category_slug' => 'sporty',
                'description' => 'Frame-focused sporty layer with clean shoulder and hip lines.',
                'image_url' => 'https://placehold.co/720x960/EBE3DB/1B1B1B?text=Diamond+Sporty',
                'purchase_link' => 'https://example.com/diamond-frame-sport-layer',
                'sort_order' => 9,
            ],
        ];

        foreach ($items as $item) {
            $categoryId = $categoryIdBySlug[$item['category_slug']] ?? null;
            if (! $categoryId) {
                continue;
            }

            FashionItem::query()->updateOrCreate(
                [
                    'title' => $item['title'],
                    'body_type' => $item['body_type'],
                ],
                [
                    'fashion_category_id' => $categoryId,
                    'description' => $item['description'],
                    'image_source' => 'url',
                    'image_path' => null,
                    'image_url' => $item['image_url'],
                    'purchase_link' => $item['purchase_link'],
                    'is_active' => true,
                    'sort_order' => $item['sort_order'],
                ]
            );
        }
    }
}
