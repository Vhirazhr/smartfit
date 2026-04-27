<?php

namespace App\Http\Controllers;

use App\Models\FashionCategory;
use App\Models\FashionItem;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    public function index()
    {
        $categories = FashionCategory::query()
            ->active()
            ->ordered()
            ->get(['id', 'name', 'slug', 'description']);

        $items = FashionItem::query()
            ->with('category:id,name,slug,is_active')
            ->active()
            ->whereHas('category', function ($query): void {
                $query->where('is_active', true);
            })
            ->ordered()
            ->get();

        $labels = config('smartfit.labels', []);
        $supportedBodyTypes = config('smartfit.body_types', array_keys($labels));

        $itemsPayload = $items->map(function (FashionItem $item) use ($labels): array {
            return [
                'id' => $item->id,
                'title' => $item->title,
                'description' => $item->description,
                'body_type' => $item->body_type,
                'body_label' => $labels[$item->body_type] ?? Str::title(str_replace('_', ' ', $item->body_type)),
                'category_slug' => $item->category?->slug,
                'category_name' => $item->category?->name,
                'image_url' => $item->display_image_url,
                'purchase_link' => $item->purchase_link,
            ];
        })->values();

        $bodyTypeCounts = $itemsPayload->groupBy('body_type')->map->count();
        $bodyFilters = collect($supportedBodyTypes)
            ->map(function (string $bodyType) use ($bodyTypeCounts, $labels): array {
                return [
                    'key' => $bodyType,
                    'label' => $labels[$bodyType] ?? Str::title(str_replace('_', ' ', $bodyType)),
                    'count' => (int) ($bodyTypeCounts[$bodyType] ?? 0),
                ];
            })
            ->filter(fn (array $item) => $item['count'] > 0)
            ->values();

        $categoryCounts = $itemsPayload->groupBy('category_slug')->map->count();
        $categoryFilters = $categories
            ->map(function (FashionCategory $category) use ($categoryCounts): array {
                return [
                    'slug' => $category->slug,
                    'name' => $category->name,
                    'count' => (int) ($categoryCounts[$category->slug] ?? 0),
                ];
            })
            ->filter(fn (array $item) => $item['count'] > 0)
            ->values();

        $bodyDescriptions = collect(config('smartfit.recommendations', []))
            ->map(fn (array $recommendation) => $recommendation['focus'] ?? '')
            ->only($supportedBodyTypes)
            ->toArray();

        return view('landing.partials.gallery2', [
            'galleryItems' => $itemsPayload,
            'bodyFilters' => $bodyFilters,
            'categoryFilters' => $categoryFilters,
            'bodyDescriptions' => $bodyDescriptions,
        ]);
    }
}
