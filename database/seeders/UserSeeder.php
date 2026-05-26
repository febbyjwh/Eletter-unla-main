<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            // ADMIN
            [
                'name' => 'Admin',
                'email' => 'admin@eletter.com',
                'password' => Hash::make('password'),
                'role_id' => 1,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // USERS
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@eletter.com',
                'password' => Hash::make('password'),
                'role_id' => 2,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'siti@eletter.com',
                'password' => Hash::make('password'),
                'role_id' => 2,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Andi Pratama',
                'email' => 'andi@eletter.com',
                'password' => Hash::make('password'),
                'role_id' => 2,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dewi Lestari',
                'email' => 'dewi@eletter.com',
                'password' => Hash::make('password'),
                'role_id' => 2,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}