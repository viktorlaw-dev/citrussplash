<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Every sellable item. IMPORTANT: price is an INTEGER in naira —
    // ₦4,550 is stored as 4550. No decimals anywhere = no money bugs (BSD rule).

    public function up(): void
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->string('slug')->unique();       // e.g. "abuja-tonic"
            $table->string('name');                 // "Abuja Tonic"
            $table->text('description')->nullable(); // the ingredients line
            $table->integer('price');               // naira
            $table->string('emoji');                // visual tile (v1 uses emoji, not photos)
            $table->enum('tag', ['pop', 'new', 'fresh'])->nullable(); // little badge
            $table->boolean('is_available')->default(true); // staff sold-out toggle
            $table->integer('sort_order');          // position within its category
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
