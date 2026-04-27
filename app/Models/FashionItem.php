<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FashionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'fashion_category_id',
        'title',
        'description',
        'body_type',
        'style_preference',
        'color_tone',
        'image_source',
        'image_path',
        'image_url',
        'purchase_link',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(FashionCategory::class, 'fashion_category_id');
    }

    public function stores(): HasMany
    {
        return $this->hasMany(FashionItemStore::class, 'fashion_item_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderByDesc('id');
    }

    public function scopeFilter($query, array $filters)
    {
        if (! empty($filters['keyword'])) {
            $keyword = trim((string) $filters['keyword']);
            $query->where('title', 'like', "%{$keyword}%");
        }

        if (! empty($filters['category_id'])) {
            $query->where('fashion_category_id', (int) $filters['category_id']);
        }

        if (! empty($filters['body_type'])) {
            $query->where('body_type', (string) $filters['body_type']);
        }

        if (array_key_exists('is_active', $filters) && $filters['is_active'] !== '' && $filters['is_active'] !== null) {
            $query->where('is_active', (bool) $filters['is_active']);
        }

        return $query;
    }

    public function getDisplayImageUrlAttribute(): string
    {
        if ($this->image_source === 'url' && filled($this->image_url)) {
            return (string) $this->image_url;
        }

        if (filled($this->image_path)) {
            $relativePath = ltrim(str_replace('\\', '/', (string) $this->image_path), '/');

            if (is_file(public_path('storage/'.$relativePath))) {
                return asset('storage/'.$relativePath);
            }

            return route('media.fashion-items.show', ['path' => $relativePath]);
        }

        return 'https://placehold.co/600x800/f5f0ed/1B1B1B?text=No+Image';
    }
}
