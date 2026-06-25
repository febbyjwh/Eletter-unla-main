<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('arsip', function (Blueprint $table) {
            if (!Schema::hasColumn('arsip', 'pembuat')) {
                $table->string('pembuat')->nullable()->after('pengirim');
            }

            if (!Schema::hasColumn('arsip', 'tujuan')) {
                $table->string('tujuan')->nullable()->after('penerima');
            }

            if (!Schema::hasColumn('arsip', 'penanda_tangan')) {
                $table->string('penanda_tangan')->nullable()->after('tujuan');
            }

            if (!Schema::hasColumn('arsip', 'pengupload')) {
                $table->string('pengupload')->nullable()->after('penanda_tangan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('arsip', function (Blueprint $table) {
            foreach (['pembuat', 'tujuan', 'penanda_tangan', 'pengupload'] as $column) {
                if (Schema::hasColumn('arsip', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
