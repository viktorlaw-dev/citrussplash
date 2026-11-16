<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Staff accounts (BSD §users). Created ONLY via `php artisan staff:create`
        // (Phase 7) — no registration route will ever exist for these.
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();                  // login handle
            $table->timestamp('email_verified_at')->nullable(); // skeleton default, unused by us
            $table->string('password');                         // bcrypt only (rule S4)
            $table->enum('role', ['owner', 'staff'])->default('staff'); // owner sees audit log, manages staff
            $table->text('totp_secret')->nullable();            // 2FA secret, encrypted at rest
            $table->boolean('totp_enabled')->default(false);    // true after QR pairing
            $table->dateTime('last_login_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
