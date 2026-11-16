<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // The lines of one order ("2x Abuja Tonic, 1x Club Sandwich").
    // name + price are SNAPSHOTS copied from menu_items at order time —
    // if an item is later deleted or re-priced, old orders still show
    // exactly what the customer actually paid. History must not change.

    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('menu_item_id')->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->string('name');  // snapshot
            $table->integer('price'); // snapshot, naira
            $table->integer('qty');  // 1–20, validated in Phase 5
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
