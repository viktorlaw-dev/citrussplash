<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // One row per order. `no` (CS-0042) is derived from the auto-increment id
    // at creation time (Phase 5, inside one database transaction).
    // The indexes power: admin order lists (status), tracker lookups (phone),
    // and the 60-minute auto-cancel job (payment_status + placed_at pair).

    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('no')->unique();          // CS-0001, CS-0042…
            $table->string('customer_name');
            $table->string('phone')->index();        // normalized 0XXXXXXXXXX
            $table->text('address')->nullable();     // filled only for delivery
            $table->enum('type', ['pickup', 'delivery']);
            $table->enum('source', ['web', 'phone']); // FR-15: staff can enter phone orders too
            $table->enum('payment_method', ['transfer']); // v1: bank transfer only
            $table->enum('payment_status', ['pending', 'confirmed'])->default('pending');
            $table->enum('status', [
                'received', 'confirmed', 'preparing', 'ready',
                'out_for_delivery', 'completed', 'cancelled',
            ])->default('received');
            $table->enum('cancel_reason', ['manual', 'payment_timeout'])->nullable();
            $table->integer('subtotal'); // naira
            $table->integer('fee');      // naira, 0 for pickup
            $table->integer('total');    // naira
            $table->integer('item_count'); // cached sum of quantities, for admin tables
            $table->dateTime('payment_confirmed_at')->nullable();
            $table->dateTime('placed_at')->nullable(); // = created_at; the Order model fills it
            $table->timestamps();

            $table->index(['payment_status', 'placed_at']); // the auto-cancel job scans this pair
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
