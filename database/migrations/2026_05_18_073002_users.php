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
            $table->string('password');
            $table->integer('role_id')->default(5)->comment('5 = guest');
            $table->longText('google_access_token')->nullable();
            $table->longText('google_refresh_token')->nullable();
            $table->timestamp('google_token_expires_at')->nullable();
            $table->string('google_drive_folder_id')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0 = nonaktif, 1 = aktif');
            $table->timestamps();
        });
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
