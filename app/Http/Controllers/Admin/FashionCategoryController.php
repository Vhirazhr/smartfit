<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFashionCategoryRequest;
use App\Http\Requests\UpdateFashionCategoryRequest;
use App\Models\FashionCategory;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FashionCategoryController extends Controller
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
        $keyword = trim((string) $request->query('keyword', ''));
        $status = $request->query('status');

        $categories = FashionCategory::query()
            ->when($keyword !== '', function ($query) use ($keyword): void {
                $query->where('name', 'like', "%{$keyword}%");
            })
            ->when($status !== null && $status !== '', function ($query) use ($status): void {
                $query->where('is_active', (bool) $status);
            })
            ->ordered()
            ->paginate(12)
            ->withQueryString();

        return view('admin.fashion-categories.index', [
            'categories' => $categories,
            'filters' => [
                'keyword' => $keyword,
                'status' => $status,
            ],
        ]);
    }

    public function create(): View
    {
        return view('admin.fashion-categories.create');
    }

    public function store(StoreFashionCategoryRequest $request): RedirectResponse
    {
        $payload = $request->validated();
        $payload['is_active'] = (bool) ($payload['is_active'] ?? false);
        $payload['sort_order'] = (int) ($payload['sort_order'] ?? 0);

        FashionCategory::create($payload);

        return redirect()->route('admin.fashion-categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(int $id): View
    {
        $category = FashionCategory::findOrFail($id);

        return view('admin.fashion-categories.edit', compact('category'));
    }

    public function update(UpdateFashionCategoryRequest $request, int $id): RedirectResponse
    {
        $category = FashionCategory::findOrFail($id);

        $payload = $request->validated();
        $payload['is_active'] = (bool) ($payload['is_active'] ?? false);
        $payload['sort_order'] = (int) ($payload['sort_order'] ?? 0);

        $category->update($payload);

        return redirect()->route('admin.fashion-categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $category = FashionCategory::findOrFail($id);

        try {
            $category->delete();

            return back()->with('success', 'Category deleted successfully.');
        } catch (QueryException) {
            return back()->with('error', 'Category cannot be deleted because it is used by one or more fashion items.');
        }
    }
}
