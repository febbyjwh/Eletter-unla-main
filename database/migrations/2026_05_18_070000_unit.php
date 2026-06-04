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
        Schema::create('unit', function (Blueprint $table) {
            $table->id('unit_id');
            $table->string('kode_unit')->unique();
            $table->string('nama_unit');
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->string('google_drive_folder_id')->nullable();
            $table->longText('google_access_token')->nullable();
            $table->longText('google_refresh_token')->nullable();
            $table->timestamp('google_token_expires_at')->nullable();
            $table->tinyInteger('status')->default(1)->comment('0 = nonaktif, 1 = aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit');
    }
};
