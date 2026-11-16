<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // The security diary: who did what, when, from where.
    // Written only by the Audit helper (never by hand). Rows are write-once —
    // they get a created_at but no updated_at, and are never edited.

    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->string('action');             // e.g. "login.failed", "order.status_changed"
            $table->json('details')->nullable();  // e.g. {"from":"received","to":"confirmed"}
            $table->string('ip_address', 45);     // 45 chars fits a full IPv6 address
            $table->string('user_agent');         // browser/device that made the request
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
