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

            // Folder Google Drive milik unit
            $table->string('google_drive_folder_id')->nullable();

            // Token Google milik unit
            $table->longText('google_access_token')->nullable();
            $table->longText('google_refresh_token')->nullable();
            $table->timestamp('google_token_expires_at')->nullable();

            $table->tinyInteger('status')
                ->default(0)
                ->comment('0=pending, 1=aktif, 2=ditolak');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
