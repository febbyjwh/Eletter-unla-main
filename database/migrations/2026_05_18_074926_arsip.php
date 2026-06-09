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
        Schema::create('arsip', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->enum('jenis_surat', ['masuk', 'keluar']);

            $table->string('no_surat');
            $table->string('pengirim');
            $table->string('penerima');
            $table->string('perihal');
            $table->date('tanggal');
            $table->string('file_surat')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('created_role_id')->nullable();
            $table->unsignedBigInteger('updated_role_id')->nullable();
            $table->timestamps();
            $table->index('jenis_surat');
            $table->index('tanggal');
            $table->index('user_id');
            $table->unique(['no_surat', 'jenis_surat']);
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->unsignedBigInteger('unit_pengirim_id')->nullable();
            $table->unsignedBigInteger('unit_penerima_id')->nullable();
            $table->foreign('unit_pengirim_id')->references('unit_id')->on('unit')->nullOnDelete();
            $table->foreign('unit_penerima_id')->references('unit_id')->on('unit')->nullOnDelete();

            // FOREIGN KEY
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('created_role_id')->references('id')->on('roles')->nullOnDelete();
            $table->foreign('updated_role_id')->references('id')->on('roles')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
