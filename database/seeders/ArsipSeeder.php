<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ArsipSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('arsip')->insert([

            [
                'user_id' => 2,
                'jenis_surat' => 'masuk',
                'no_surat' => 'SM-001/USR/2026',
                'pengirim' => 'Rektorat UNLA',
                'penerima' => 'Budi Santoso',
                'perihal' => 'Undangan Rapat Akademik',
                'tanggal' => Carbon::now()->subDays(10),
                'file_surat' => null,

                'created_by' => 2,
                'updated_by' => 2,
                'created_role_id' => 2,
                'updated_role_id' => 2,

                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'jenis_surat' => 'keluar',
                'no_surat' => 'SK-001/USR/2026',
                'pengirim' => 'Budi Santoso',
                'penerima' => 'Rektorat UNLA',
                'perihal' => 'Konfirmasi Kehadiran',
                'tanggal' => Carbon::now()->subDays(7),
                'file_surat' => null,

                'created_by' => 2,
                'updated_by' => 2,
                'created_role_id' => 2,
                'updated_role_id' => 2,

                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'user_id' => 3,
                'jenis_surat' => 'masuk',
                'no_surat' => 'SM-002/USR/2026',
                'pengirim' => 'BAAK',
                'penerima' => 'Siti Nurhaliza',
                'perihal' => 'Informasi Registrasi',
                'tanggal' => Carbon::now()->subDays(6),
                'file_surat' => null,

                'created_by' => 1,
                'updated_by' => 1,
                'created_role_id' => 1,
                'updated_role_id' => 1,

                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'user_id' => 4,
                'jenis_surat' => 'masuk',
                'no_surat' => 'SM-003/USR/2026',
                'pengirim' => 'Fakultas Teknik',
                'penerima' => 'Andi Pratama',
                'perihal' => 'Jadwal Seminar Proposal',
                'tanggal' => Carbon::now()->subDays(4),
                'file_surat' => null,

                'created_by' => 1,
                'updated_by' => 1,
                'created_role_id' => 1,
                'updated_role_id' => 1,

                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 4,
                'jenis_surat' => 'keluar',
                'no_surat' => 'SK-002/USR/2026',
                'pengirim' => 'Andi Pratama',
                'penerima' => 'Fakultas Teknik',
                'perihal' => 'Pengajuan Revisi Proposal',
                'tanggal' => Carbon::now()->subDays(2),
                'file_surat' => null,

                'created_by' => 4,
                'updated_by' => 4,
                'created_role_id' => 2,
                'updated_role_id' => 2,

                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'user_id' => 5,
                'jenis_surat' => 'masuk',
                'no_surat' => 'SM-004/USR/2026',
                'pengirim' => 'Kemahasiswaan',
                'penerima' => 'Dewi Lestari',
                'perihal' => 'Informasi Beasiswa',
                'tanggal' => Carbon::now()->subDay(),
                'file_surat' => null,

                'created_by' => 1,
                'updated_by' => 1,
                'created_role_id' => 1,
                'updated_role_id' => 1,

                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}