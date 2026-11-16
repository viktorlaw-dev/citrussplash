<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database (BSD §6).
     * Staff accounts are deliberately NOT seeded — they are created only via
     * `php artisan staff:create` (Phase 7), never through the web.
     */
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            MenuItemSeeder::class,
            SettingSeeder::class,
        ]);
    }
}
