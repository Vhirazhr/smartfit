<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFashionItemRequest;
use App\Http\Requests\UpdateFashionItemRequest;
use App\Models\FashionCategory;
use App\Models\FashionItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class FashionItemController extends Controller
{
    public function __construct()
    {
        $this->middleware(static function (Request $request, $next) {
            if (! session()->has('admin')) {
                return redirect('/admin/login');
            }

            return $next($request);
        });
    }

    public function index(Request $request): View
    {
        $filters = [
            'keyword' => trim((string) $request->query('keyword', '')),
            'category_id' => $request->query('category_id'),
            'body_type' => $request->query('body_type'),
            'is_active' => $request->query('is_active'),
        ];

        $items = FashionItem::query()
            ->with('category')
            ->filter($filters)
            ->ordered()
            ->paginate(12)
            ->withQueryString();

        return view('admin.fashion-items.index', [
            'items' => $items,
            'categories' => FashionCategory::query()->ordered()->get(),
            'bodyTypes' => config('smartfit.body_types', []),
            'labels' => config('smartfit.labels', []),
            'filters' => $filters,
        ]);
    }

    public function create(): View
    {
        return view('admin.fashion-items.create', [
            'categories' => FashionCategory::query()->active()->ordered()->get(),
            'bodyTypes' => config('smartfit.body_types', []),
            'labels' => config('smartfit.labels', []),
        ]);
    }

    public function store(StoreFashionItemRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $payload = [
            'fashion_category_id' => (int) $validated['fashion_category_id'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'body_type' => $validated['body_type'],
            'image_source' => $validated['image_source'],
            'purchase_link' => $validated['purchase_link'] ?? null,
            'is_active' => (bool) ($validated['is_active'] ?? false),
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
            'image_path' => null,
            'image_url' => null,
        ];

        if ($payload['image_source'] === 'upload' && $request->hasFile('image_file')) {
            $payload['image_path'] = $request->file('image_file')->store('fashion-items', 'public');
        }

        if ($payload['image_source'] === 'url') {
            $payload['image_url'] = $validated['image_url'] ?? null;
        }

        FashionItem::create($payload);

        return redirect()->route('admin.fashion-items.index')->with('success', 'Fashion item created successfully.');
    }

    public function edit(int $id): View
    {
        $item = FashionItem::findOrFail($id);

        return view('admin.fashion-items.edit', [
            'item' => $item,
            'categories' => FashionCategory::query()->ordered()->get(),
            'bodyTypes' => config('smartfit.body_types', []),
            'labels' => config('smartfit.labels', []),
        ]);
    }

    public function update(UpdateFashionItemRequest $request, int $id): RedirectResponse
    {
        $item = FashionItem::findOrFail($id);
        $validated = $request->validated();

        $payload = [
            'fashion_category_id' => (int) $validated['fashion_category_id'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'body_type' => $validated['body_type'],
            'image_source' => $validated['image_source'],
            'purchase_link' => $validated['purchase_link'] ?? null,
            'is_active' => (bool) ($validated['is_active'] ?? false),
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
        ];

        if ($payload['image_source'] === 'upload') {
            if ($request->hasFile('image_file')) {
                $this->deleteUploadedImageIfExists($item);
                $payload['image_path'] = $request->file('image_file')->store('fashion-items', 'public');
            } else {
                $payload['image_path'] = $item->image_source === 'upload' ? $item->image_path : null;
            }

            $payload['image_url'] = null;
        }

        if ($payload['image_source'] === 'url') {
            $this->deleteUploadedImageIfExists($item);
            $payload['image_url'] = $validated['image_url'] ?? null;
            $payload['image_path'] = null;
        }

        $item->update($payload);

        return redirect()->route('admin.fashion-items.index')->with('success', 'Fashion item updated successfully.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $item = FashionItem::findOrFail($id);
        $this->deleteUploadedImageIfExists($item);
        $item->delete();

        return back()->with('success', 'Fashion item deleted successfully.');
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
