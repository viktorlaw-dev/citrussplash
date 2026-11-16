<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['order_id', 'menu_item_id', 'name', 'price', 'qty'])]
class OrderItem extends Model
{
    // One line of an order ("2x Abuja Tonic"). name + price are snapshots
    // taken at order time — history never changes, even if the menu does.

    public $timestamps = false; // order lines don't need created_at/updated_at

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function menuItem(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class);
    }

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'qty' => 'integer',
        ];
    }
}
