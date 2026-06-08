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
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('email')->unique();

            // Bisa null karena login Google
            $table->string('password')->nullable();

            // Relasi ke unit jika role = unit
            $table->foreignId('unit_id')
                ->nullable()
                ->constrained('unit', 'unit_id')
                ->nullOnDelete();

            // Google OAuth
            $table->string('google_id')->nullable()->unique();

            $table->longText('google_access_token')->nullable();
            $table->longText('google_refresh_token')->nullable();
            $table->timestamp('google_token_expires_at')->nullable();

            $table->tinyInteger('role_id')
                ->default(2)
                ->comment('1=admin, 2=user, 3=unit');

            $table->tinyInteger('status')
                ->default(0)
                ->comment('0=pending, 1=aktif, 2=ditolak');

            $table->rememberToken();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
