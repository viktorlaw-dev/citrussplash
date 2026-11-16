<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Menu groups, in the exact order they appear on the page.
    // A "slug" is a URL-safe nickname: "smoothies" instead of "Smoothies".

    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique(); // "smoothies", "sandwiches", "salads"
            $table->string('label');          // "Smoothies", "Salads & More"
            $table->string('emoji');          // "🍊" — the little icon in the tab bar
            $table->integer('sort_order');    // display order (1, 2, 3)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
