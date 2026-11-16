<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['key', 'value'])]
class Setting extends Model
{
    // Key/value home for every store-tunable value. The rest of the app reads
    // one with Setting::get('delivery_fee'); the admin Settings page edits
    // this table — no code change is ever needed to change a value.

    protected static function booted(): void
    {
        // Any write to settings instantly invalidates the cache, so changes
        // appear everywhere the moment staff clicks Save.
        static::saved(fn () => cache()->forget('settings.all'));
        static::deleted(fn () => cache()->forget('settings.all'));
    }

    // Read one value, with a light 60-second cache so pages don't re-query
    // the same setting over and over.
    public static function get(string $key, ?string $default = null): ?string
    {
        $rows = cache()->remember(
            'settings.all',
            60,
            fn () => self::query()->pluck('value', 'key')->all()
        );

        return $rows[$key] ?? $default;
    }
}
