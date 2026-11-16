<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'no', 'customer_name', 'phone', 'address', 'type', 'source',
    'payment_method', 'payment_status', 'status', 'cancel_reason',
    'subtotal', 'fee', 'total', 'item_count', 'payment_confirmed_at', 'placed_at',
])]
class Order extends Model
{
    // One customer order. In Phase 5, `no` (CS-0042) and placed_at are filled
    // on create inside one database transaction — totals priced from the DB
    // only, never from the browser (rule S1).

    public function lines(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    protected function casts(): array
    {
        return [
            'subtotal' => 'integer',
            'fee' => 'integer',
            'total' => 'integer',
            'item_count' => 'integer',
            'payment_confirmed_at' => 'datetime',
            'placed_at' => 'datetime',
        ];
    }
}
