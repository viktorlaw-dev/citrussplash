<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['category_id', 'slug', 'name', 'description', 'price', 'emoji', 'tag', 'is_available', 'sort_order'])]
class MenuItem extends Model
{
    // One sellable item. Price is an INTEGER in naira (4550 = ₦4,550).

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'is_available' => 'boolean',
        ];
    }

    // Used as MenuItem::available() — only items customers can currently order.
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }
}
