<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // =========================
        // PERMISSIONS
        // =========================
        DB::table('permissions')->insert([
            ['name' => 'view_dashboard',     'description' => 'Melihat dashboard utama'],
            ['name' => 'manage_letters_in',  'description' => 'Mengelola surat masuk'],
            ['name' => 'manage_letters_out', 'description' => 'Mengelola surat keluar'],
            ['name' => 'manage_templates',   'description' => 'Mengelola template surat'],
            ['name' => 'manage_disposition', 'description' => 'Mengelola disposisi surat'],
            ['name' => 'view_reports',       'description' => 'Melihat laporan'],
            ['name' => 'manage_settings',    'description' => 'Mengatur konfigurasi aplikasi'],
            ['name' => 'manage_users',       'description' => 'Mengelola data user'],
            ['name' => 'manage_roles',       'description' => 'Mengelola role dan permission'],
            ['name' => 'manage_letters',     'description' => 'Melihat surat keluar masuk'],
        ]);

        // =========================
        // ROLES
        // =========================
        DB::table('roles')->insert([
            ['name' => 'admin',     'description' => 'Memiliki akses penuh ke sistem'],
            ['name' => 'staff',     'description' => 'Mengelola surat masuk/keluar'],
            ['name' => 'dosen',     'description' => 'Mengakses surat akademik'],
            ['name' => 'mahasiswa', 'description' => 'Mengakses surat pribadi'],
            ['name' => 'guest', 'description' => 'Akun guest'],
        ]);

        // =========================
        // ROLE - PERMISSION
        // =========================
        DB::table('role_permission')->insert([
            ['role_id' => 1, 'permission_id' => 1],
            ['role_id' => 2, 'permission_id' => 1],
            ['role_id' => 3, 'permission_id' => 1],

            ['role_id' => 1, 'permission_id' => 2],
            ['role_id' => 2, 'permission_id' => 2],
            ['role_id' => 3, 'permission_id' => 2],
            ['role_id' => 4, 'permission_id' => 2],

            ['role_id' => 1, 'permission_id' => 3],
            ['role_id' => 2, 'permission_id' => 3],

            ['role_id' => 1, 'permission_id' => 4],
            ['role_id' => 2, 'permission_id' => 4],

            ['role_id' => 1, 'permission_id' => 5],
            ['role_id' => 2, 'permission_id' => 5],

            ['role_id' => 1, 'permission_id' => 6],
            ['role_id' => 2, 'permission_id' => 6],
            ['role_id' => 3, 'permission_id' => 6],

            ['role_id' => 1, 'permission_id' => 7],
            ['role_id' => 1, 'permission_id' => 8],
            ['role_id' => 1, 'permission_id' => 9],

            ['role_id' => 1, 'permission_id' => 10],
            ['role_id' => 2, 'permission_id' => 10],
            ['role_id' => 3, 'permission_id' => 10],
            ['role_id' => 4, 'permission_id' => 10],
        ]);

        // =========================
        // USERS
        // =========================
        DB::table('users')->insert([
            [
                'name' => 'Staff TU',
                'email' => 'staff@example.com',
                'password' => Hash::make('password'),
                'created_at' => Carbon::parse('2025-09-09 08:34:49'),
                'updated_at' => Carbon::parse('2025-09-10 09:47:14'),
                'role_id' => 1,
                'status' => 1,
            ],
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@gmail.com',
                'password' => Hash::make('password'),
                'created_at' => Carbon::parse('2025-09-09 17:19:48'),
                'updated_at' => Carbon::parse('2025-09-24 20:22:20'),
                'role_id' => 1,
                'status' => 1,
            ],
            [
                'name' => 'Superadmin',
                'email' => 'spadmin@gmail.com',
                'password' => Hash::make('password'),
                'created_at' => Carbon::parse('2025-09-10 04:19:59'),
                'updated_at' => Carbon::parse('2025-09-10 04:19:59'),
                'role_id' => 1,
                'status' => 1,

            ],
            [
                'name' => 'soni',
                'email' => 'soni7@gmail.com',
                'password' => Hash::make('password'),
                'created_at' => Carbon::parse('2025-09-10 08:41:26'),
                'updated_at' => Carbon::parse('2025-09-10 08:41:26'),
                'role_id' => 4,
                'status' => 1,
            ],
            [
                'name' => 'Mahasiswa',
                'email' => 'mhs@gmail.com',
                'password' => Hash::make('password'),
                'created_at' => Carbon::parse('2025-09-11 03:30:26'),
                'updated_at' => Carbon::parse('2025-09-11 03:30:26'),
                'role_id' => 4,
                'status' => 0,
            ],
            [
                'name' => 'dosen',
                'email' => 'dosen@gmail.com',
                'password' => Hash::make('password'),
                'created_at' => Carbon::parse('2025-09-11 04:12:09'),
                'updated_at' => Carbon::parse('2025-09-11 04:12:24'),
                'role_id' => 3,
                'status' => 1,
            ],
        ]);
    }
}
