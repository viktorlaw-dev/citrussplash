<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Response;

class MenuDataController extends Controller
{
    /**
     * Serve the menu as a JavaScript file — the EXACT contract the old
     * static js/menu-data.js had (Architecture §2): window.CS_MENU = {...}
     * with cats[] and items[]. Page scripts notice nothing; the data now
     * lives in the database and this file can never go stale.
     *
     * Cached for 60s; Phase 7's menu editor must forget the 'menu-data.js'
     * cache key when items change (same pattern as Setting::get()).
     */
    public function __invoke(): Response
    {
        $payload = cache()->remember('menu-data.js', 60, function () {
            $cats = Category::query()
                ->orderBy('sort_order')
                ->get()
                ->map(fn (Category $c) => [
                    'id' => $c->slug,
                    'label' => $c->label,
                    'emoji' => $c->emoji,
                ])
                ->values()
                ->all();

            // Items flattened category-by-category in display order —
            // the same ordering the seeded menu file had.
            $items = Category::query()
                ->orderBy('sort_order')
                ->with('items')
                ->get()
                ->flatMap(fn (Category $c) => $c->items
                    ->filter(fn ($item) => $item->is_available) // sold-out items vanish client-side
                    ->map(fn ($item) => [
                        'id' => $item->slug,
                        'name' => $item->name,
                        'desc' => $item->description,
                        'price' => (int) $item->price, // naira, integer (rule D1)
                        'cat' => $c->slug,
                        'emoji' => $item->emoji,
                        'tag' => $item->tag,
                    ]))
                ->values()
                ->all();

            return ['cats' => $cats, 'items' => $items];
        });

        return response(
            'window.CS_MENU = '.json_encode($payload, JSON_UNESCAPED_UNICODE).';',
            200,
            ['Content-Type' => 'text/javascript; charset=utf-8']
        );
    }
}
