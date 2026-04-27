<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FashionItemStore extends Model
{
    use HasFactory;

    protected $fillable = [
        'fashion_item_id',
        'store_name',
        'store_link',
        'sort_order',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(FashionItem::class, 'fashion_item_id');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }
}
