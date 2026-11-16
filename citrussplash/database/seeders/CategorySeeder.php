<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    // The 3 real menu groups, in display order — exactly as the
    // prototype's tab bar shows them.
    public function run(): void
    {
        $categories = [
            ['slug' => 'smoothies', 'label' => 'Smoothies', 'emoji' => '🍊', 'sort_order' => 1],
            ['slug' => 'sandwiches', 'label' => 'Sandwiches', 'emoji' => '🥪', 'sort_order' => 2],
            ['slug' => 'salads', 'label' => 'Salads & More', 'emoji' => '🥗', 'sort_order' => 3],
        ];

        foreach ($categories as $category) {
            // updateOrCreate = safe to run twice: it updates instead of duplicating.
            Category::updateOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
