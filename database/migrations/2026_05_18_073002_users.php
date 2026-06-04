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
            $table->string('password')->nullable();
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->foreign('unit_id')
                ->references('unit_id')
                ->on('unit')
                ->nullOnDelete();
            $table->integer('role_id')->default(5)->comment('2 = user');
            $table->longText('google_access_token')->nullable();
            $table->longText('google_refresh_token')->nullable();
            $table->timestamp('google_token_expires_at')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0 = nonaktif, 1 = aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('drive_access_completed');
        });
    }
};
