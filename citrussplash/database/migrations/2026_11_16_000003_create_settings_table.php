<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Store-tunable values (bank details, delivery fee, WhatsApp templates…)
    // live here as editable key/value rows. The admin Settings page edits
    // THIS table — changing a price or fee never requires touching code.

    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // e.g. "delivery_fee"
            $table->text('value');           // e.g. "1500"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
