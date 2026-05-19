<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_masuk', function (Blueprint $table) {
            $table->id();
            $table->string('no_surat')->nullable();
            $table->string('pengirim')->nullable();
            $table->string('penerima')->nullable();
            $table->string('perihal')->nullable();
            $table->date('tanggal')->nullable();
            $table->string('file_surat')->nullable();

            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('created_role_id')->nullable()->constrained('roles')->nullOnDelete();
            $table->foreignId('updated_role_id')->nullable()->constrained('roles')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_masuk');
    }
};
