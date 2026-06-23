<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surat_masuk', function (Blueprint $table) {
            if (!Schema::hasColumn('surat_masuk', 'penanda_tangan')) {
                $table->string('penanda_tangan')->nullable()->after('penerima');
            }

            if (!Schema::hasColumn('surat_masuk', 'tujuan')) {
                $table->string('tujuan')->nullable()->after('penanda_tangan');
            }

            if (!Schema::hasColumn('surat_masuk', 'pengupload')) {
                $table->string('pengupload')->nullable()->after('tujuan');
            }
        });

        if (!Schema::hasTable('surat_keluar')) {
            Schema::create('surat_keluar', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('no_surat')->nullable();
                $table->string('pengirim')->nullable();
                $table->string('pembuat')->nullable();
                $table->string('tujuan')->nullable();
                $table->string('penanda_tangan')->nullable();
                $table->string('pengupload')->nullable();
                $table->string('penerima')->nullable();
                $table->string('perihal')->nullable();
                $table->date('tanggal')->nullable();
                $table->string('file_surat')->nullable();
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('created_role_id')->nullable()->constrained('roles')->nullOnDelete();
                $table->foreignId('updated_role_id')->nullable()->constrained('roles')->nullOnDelete();
                $table->timestamps();
            });

            return;
        }

        Schema::table('surat_keluar', function (Blueprint $table) {
            if (!Schema::hasColumn('surat_keluar', 'pembuat')) {
                $table->string('pembuat')->nullable()->after('pengirim');
            }

            if (!Schema::hasColumn('surat_keluar', 'tujuan')) {
                $table->string('tujuan')->nullable()->after('pembuat');
            }

            if (!Schema::hasColumn('surat_keluar', 'penanda_tangan')) {
                $table->string('penanda_tangan')->nullable()->after('tujuan');
            }

            if (!Schema::hasColumn('surat_keluar', 'pengupload')) {
                $table->string('pengupload')->nullable()->after('penanda_tangan');
            }
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('surat_keluar')) {
            Schema::table('surat_keluar', function (Blueprint $table) {
                foreach (['pembuat', 'tujuan', 'penanda_tangan', 'pengupload'] as $column) {
                    if (Schema::hasColumn('surat_keluar', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }

        if (Schema::hasTable('surat_masuk')) {
            Schema::table('surat_masuk', function (Blueprint $table) {
                foreach (['penanda_tangan', 'tujuan', 'pengupload'] as $column) {
                    if (Schema::hasColumn('surat_masuk', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};
