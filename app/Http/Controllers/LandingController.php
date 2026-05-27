<?php

namespace App\Http\Controllers;

use App\Models\FashionItem;
use Illuminate\Support\Str;

class LandingController extends Controller
{
    public function index()
    {
        $labels = config('smartfit.labels', []);

        $galleryItems = FashionItem::query()
            ->with('category')
            ->active()
            ->take(8)
            ->get()
            ->map(function ($item) use ($labels) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'body_label' => $labels[$item->body_type]
                        ?? Str::title(str_replace('_', ' ', $item->body_type)),
                    'category_name' => $item->category?->name,
                    'image_url' => $item->display_image_url,
                ];
            });

        return view('landing.index', compact('galleryItems'));
    }
}