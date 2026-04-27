<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FashionCategory;
use App\Models\FashionItem;
use App\Models\FashionItemStore;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DashboardController extends Controller
{
    private const STYLE_LABELS = [
        'casual' => 'Casual',
        'formal' => 'Formal',
        'sporty' => 'Sporty',
        'classic' => 'Classic',
        'bohemian' => 'Bohemian',
    ];

    private const COLOR_LABELS = [
        'light' => 'Light',
        'bright' => 'Bright',
        'neutral' => 'Neutral',
        'dark' => 'Dark',
        'earth' => 'Earth',
    ];

    public function __construct()
    {
        $this->middleware(static function (Request $request, $next) {
            if (! session()->has('admin')) {
                return redirect('/admin/login');
            }

            return $next($request);
        });
    }

    public function index(): View
    {
        $labels = config('smartfit.labels', []);

        $items = FashionItem::query()
            ->with([
                'category:id,name,slug',
                'stores' => fn ($query) => $query->ordered(),
            ])
            ->ordered()
            ->get();

        $fashionItemsPayload = $items
            ->map(function (FashionItem $item) use ($labels): array {
                $styleKey = $this->resolveStyleKey($item);
                $colorKey = $this->resolveColorKey($item->color_tone);

                $stores = $item->stores
                    ->map(fn (FashionItemStore $store): array => [
                        'name' => $store->store_name,
                        'link' => $store->store_link,
                    ])
                    ->values()
                    ->all();

                if (empty($stores) && filled($item->purchase_link)) {
                    $stores[] = [
                        'name' => 'Store Link',
                        'link' => $item->purchase_link,
                    ];
                }

                $bodyLabel = $labels[$item->body_type] ?? Str::title(str_replace('_', ' ', $item->body_type));

                return [
                    'id' => $item->id,
                    'image' => $item->display_image_url,
                    'name' => $item->title,
                    'bodyType' => $item->body_type,
                    'bodyTypeLabel' => $bodyLabel,
                    'style' => $styleKey,
                    'styleLabel' => self::STYLE_LABELS[$styleKey] ?? Str::title($styleKey),
                    'color' => $colorKey,
                    'colorLabel' => self::COLOR_LABELS[$colorKey] ?? self::COLOR_LABELS['neutral'],
                    'description' => (string) ($item->description ?? ''),
                    'stores' => $stores,
                    'editUrl' => route('admin.fashion-items.edit', $item->id),
                ];
            })
            ->values();

        $totalStores = $fashionItemsPayload
            ->flatMap(fn (array $item) => collect($item['stores'])->pluck('name'))
            ->map(fn (?string $name) => strtolower((string) $name))
            ->filter()
            ->unique()
            ->count();

        return view('admin.dashboard', [
            'fashionItemsPayload' => $fashionItemsPayload,
            'stats' => [
                'total_items' => $fashionItemsPayload->count(),
                'total_stores' => $totalStores,
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $normalizedBodyType = $this->normalizeBodyType((string) $request->input('body_type', ''));
        $normalizedStylePreference = $this->normalizeStylePreference((string) $request->input('style_preference', ''));
        $normalizedColorTone = $this->normalizeColorTone((string) $request->input('color_tone', ''));
        $normalizedImageSource = $this->normalizeImageSource((string) $request->input('image_source', 'upload'));
        $normalizedImageUrl = $this->normalizeImageUrl((string) $request->input('image_url', ''));
        $storesPayload = $this->parseStoresPayload($request->input('stores_payload'));

        $request->merge([
            'body_type' => $normalizedBodyType,
            'style_preference' => $normalizedStylePreference,
            'color_tone' => $normalizedColorTone,
            'image_source' => $normalizedImageSource,
            'image_url' => $normalizedImageUrl,
            'stores' => $storesPayload,
        ]);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'body_type' => ['required', 'in:'.implode(',', config('smartfit.body_types', []))],
            'style_preference' => ['required', 'in:'.implode(',', array_keys(self::STYLE_LABELS))],
            'color_tone' => ['nullable', 'in:'.implode(',', array_keys(self::COLOR_LABELS))],
            'description' => ['required', 'string'],
            'image_source' => ['required', 'in:upload,url'],
            'image_file' => ['required_if:image_source,upload', 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'image_url' => ['required_if:image_source,url', 'nullable', 'url', 'max:2048'],
            'stores' => ['required', 'array', 'min:1'],
            'stores.*.name' => ['required', 'string', 'max:120'],
            'stores.*.link' => ['nullable', 'url', 'max:2048'],
        ], [
            'stores.required' => 'Tambahkan minimal satu toko penyedia.',
            'stores.min' => 'Tambahkan minimal satu toko penyedia.',
        ]);

        $category = $this->resolveCategoryFromStyle($validated['style_preference']);
        $imagePath = null;
        $imageUrl = null;

        if ($validated['image_source'] === 'upload' && $request->hasFile('image_file')) {
            $imagePath = $request->file('image_file')->store('fashion-items', 'public');
        }

        if ($validated['image_source'] === 'url') {
            $imageUrl = $validated['image_url'] ?? null;
        }

        $primaryStoreLink = collect($validated['stores'])
            ->pluck('link')
            ->filter()
            ->first();

        $item = FashionItem::query()->create([
            'fashion_category_id' => $category->id,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'body_type' => $validated['body_type'],
            'style_preference' => $validated['style_preference'],
            'color_tone' => $validated['color_tone'] ?? null,
            'image_source' => $validated['image_source'],
            'image_path' => $imagePath,
            'image_url' => $imageUrl,
            'purchase_link' => $primaryStoreLink,
            'is_active' => true,
            'sort_order' => ((int) FashionItem::query()->max('sort_order')) + 1,
        ]);

        $stores = collect($validated['stores'])
            ->values()
            ->map(fn (array $store, int $index): array => [
                'store_name' => $store['name'],
                'store_link' => $store['link'] ?? null,
                'sort_order' => $index,
            ])
            ->all();

        $item->stores()->createMany($stores);

        return redirect()->route('admin.dashboard')->with('success', 'Fashion item berhasil disimpan.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $item = FashionItem::query()->findOrFail($id);
        $this->deleteUploadedImageIfExists($item);
        $item->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Fashion item berhasil dihapus.');
    }

    private function parseStoresPayload(mixed $storesPayload): array
    {
        $rawStores = [];

        if (is_array($storesPayload)) {
            $rawStores = $storesPayload;
        }

        if (is_string($storesPayload)) {
            $decoded = json_decode($storesPayload, true);
            if (is_array($decoded)) {
                $rawStores = $decoded;
            }
        }

        return collect($rawStores)
            ->filter(fn (mixed $item): bool => is_array($item))
            ->map(function (array $store): array {
                $name = trim((string) ($store['name'] ?? ''));
                $link = trim((string) ($store['link'] ?? ''));

                return [
                    'name' => $name,
                    'link' => $link === '' || $link === '#' ? null : $link,
                ];
            })
            ->filter(fn (array $store): bool => $store['name'] !== '')
            ->values()
            ->all();
    }

    private function normalizeBodyType(string $bodyType): string
    {
        $normalized = strtolower(str_replace(['-', ' '], '_', trim($bodyType)));

        return match ($normalized) {
            'inverted', 'invertedtriangle' => 'inverted_triangle',
            'y' => 'y_shape',
            default => $normalized,
        };
    }

    private function normalizeStylePreference(string $stylePreference): string
    {
        return strtolower(str_replace(['-', ' '], '_', trim($stylePreference)));
    }

    private function normalizeColorTone(string $colorTone): ?string
    {
        $normalized = strtolower(str_replace(['-', ' '], '_', trim($colorTone)));

        return $normalized === '' ? null : $normalized;
    }

    private function normalizeImageSource(string $imageSource): string
    {
        $normalized = strtolower(trim($imageSource));

        if (! in_array($normalized, ['upload', 'url'], true)) {
            return 'upload';
        }

        return $normalized;
    }

    private function normalizeImageUrl(string $imageUrl): ?string
    {
        $normalized = trim($imageUrl);

        return $normalized === '' ? null : $normalized;
    }

    private function resolveCategoryFromStyle(string $stylePreference): FashionCategory
    {
        $categoryMap = [
            'casual' => ['name' => 'Casual', 'slug' => 'casual', 'sort_order' => 2],
            'formal' => ['name' => 'Formal', 'slug' => 'formal', 'sort_order' => 1],
            'sporty' => ['name' => 'Sporty', 'slug' => 'sporty', 'sort_order' => 5],
            'classic' => ['name' => 'Classic', 'slug' => 'classic', 'sort_order' => 6],
            'bohemian' => ['name' => 'Bohemian', 'slug' => 'bohemian', 'sort_order' => 7],
        ];

        $selected = $categoryMap[$stylePreference] ?? [
            'name' => Str::title(str_replace('_', ' ', $stylePreference)),
            'slug' => Str::slug($stylePreference),
            'sort_order' => 10,
        ];

        return FashionCategory::query()->updateOrCreate(
            ['slug' => $selected['slug']],
            [
                'name' => $selected['name'],
                'is_active' => true,
                'sort_order' => $selected['sort_order'],
            ]
        );
    }

    private function resolveStyleKey(FashionItem $item): string
    {
        if (filled($item->style_preference)) {
            return $this->normalizeStylePreference((string) $item->style_preference);
        }

        $slug = strtolower((string) ($item->category?->slug ?? ''));

        return match ($slug) {
            'office' => 'classic',
            'party' => 'bohemian',
            'casual', 'formal', 'sporty', 'classic', 'bohemian' => $slug,
            default => 'formal',
        };
    }

    private function resolveColorKey(?string $colorTone): string
    {
        $normalized = $this->normalizeColorTone((string) $colorTone);

        if ($normalized !== null && isset(self::COLOR_LABELS[$normalized])) {
            return $normalized;
        }

        return 'neutral';
    }

    private function deleteUploadedImageIfExists(FashionItem $item): void
    {
        if ($item->image_source !== 'upload' || blank($item->image_path)) {
            return;
        }

        if (Storage::disk('public')->exists((string) $item->image_path)) {
            Storage::disk('public')->delete((string) $item->image_path);
        }
    }
}
