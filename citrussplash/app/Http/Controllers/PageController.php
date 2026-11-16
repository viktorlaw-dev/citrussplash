<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Contracts\View\View;

class PageController extends Controller
{
    // Home page shows the category tiles in the PROTOTYPE'S exact order
    // (Smoothies, Salads, Sandwiches-wide). The menu page, by contrast,
    // follows the database sort_order (Smoothies, Sandwiches, Salads).
    // Each page stays 1:1 with its approved design (rule F7).
    private const HOME_TILE_ORDER = ['smoothies', 'salads', 'sandwiches'];

    // Tile descriptor text per category, exactly as the prototype wrote it.
    private const TILE_DESC = [
        'smoothies' => 'Squeezed & blended when you order',
        'salads' => 'Crisp, clean & delicious',
        'sandwiches' => 'Toasted, loaded & melty',
    ];

    // Tile accent class (drives the gradient) per category slug.
    private const TILE_CLASS = [
        'smoothies' => 'juice',
        'salads' => 'salad',
        'sandwiches' => 'sand',
    ];

    // The four "Most-ordered this week" cards, exactly as the prototype chose.
    private const FEATURED_SLUGS = ['club', 'abuja-tonic', 'sp-special', 'orange-juice'];

    // Card-foot subtitle per featured item ("Ready ~15 min" vs "Squeezed fresh").
    private const FEATURED_NOTES = [
        'club' => 'Ready ~15 min',
        'abuja-tonic' => 'Squeezed fresh',
        'sp-special' => 'Ready ~15 min',
        'orange-juice' => 'Squeezed fresh',
    ];

    // DB tag → badge class + label, exactly as menu.js maps them.
    private const BADGES = [
        'pop' => ['pop', 'Popular'],
        'new' => ['new', 'New'],
        'fresh' => ['veg', 'Fresh'],
    ];

    public function home(): View
    {
        // Categories with item counts, keyed by slug for the tile builder.
        $categories = Category::withCount('items')->orderBy('sort_order')->get()->keyBy('slug');

        $catTiles = collect(self::HOME_TILE_ORDER)->values()->map(function ($slug, $i) use ($categories) {
            $category = $categories[$slug];

            return [
                'slug' => $slug,
                'label' => $category->label,
                'emoji' => $category->emoji,
                'class' => self::TILE_CLASS[$slug],
                'desc' => self::TILE_DESC[$slug],
                'count' => $category->items_count, // "49 options" — live from the DB
                'delay' => $i === 0 ? '' : 'd'.$i, // staggered reveal, like the prototype
            ];
        })->all();

        // "From ₦…" prices are the lowest prices in each category,
        // computed from the DB so a price change never needs a code edit.
        $mins = [
            'smoothies' => $this->minPrice('smoothies'),
            'sandwiches' => $this->minPrice('sandwiches'),
            'all' => (int) MenuItem::query()->min('price'), // the hero price tag
        ];

        $featured = collect(self::FEATURED_SLUGS)->values()->map(function ($slug, $i) {
            $item = MenuItem::with('category')->where('slug', $slug)->firstOrFail();
            [$badgeClass, $badgeLabel] = self::BADGES[$item->tag] ?? ['', ''];

            return [
                'slug' => $item->slug,
                'name' => $item->name,
                'description' => $item->description,
                'price' => $item->price,
                'emoji' => $item->emoji,
                'visual' => match ($item->category->slug) {
                    'sandwiches' => 'sand',
                    'salads' => 'salad',
                    default => 'juice',
                },
                'mono' => $this->initials($item->name), // ghost letters on the tile
                'cat_label' => $item->category->label,
                'badge_class' => $badgeClass,
                'badge_label' => $badgeLabel,
                'note' => self::FEATURED_NOTES[$slug],
                'delay' => $i === 0 ? '' : 'd'.$i,
            ];
        })->all();

        return view('home', [
            'page' => 'home',
            'catTiles' => $catTiles,
            'mins' => $mins,
            'featured' => $featured,
        ]);
    }

    public function menu(): View
    {
        // The cards themselves are rendered client-side from /js/menu-data.js,
        // so this view only carries the shell (search, tabs, empty state).
        return view('menu', ['page' => 'menu']);
    }

    // ---- Placeholder pages; real builds land later in Phase 4 ----

    public function checkout(): View
    {
        return view('checkout', ['page' => 'checkout']);
    }

    public function status(): View
    {
        return view('status', ['page' => 'status']);
    }

    public function about(): View
    {
        return view('about', ['page' => 'about']);
    }

    // ---- small helpers ----

    // Lowest price within one category (integers in naira — rule D1).
    private function minPrice(string $categorySlug): int
    {
        return (int) MenuItem::query()
            ->whereHas('category', fn ($q) => $q->where('slug', $categorySlug))
            ->min('price');
    }

    // First letters of the first two words, e.g. "Orange Juice" → "OJ".
    // Same rule the prototype's menu.js uses for its ghost letters.
    private function initials(string $name): string
    {
        $words = preg_split('/\s+/', trim($name)) ?: [];

        return strtoupper(substr($words[0] ?? 'C', 0, 1).substr($words[1] ?? 'S', 0, 1));
    }
}
