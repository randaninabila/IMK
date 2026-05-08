<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // ADMIN
        DB::table('users')->updateOrInsert(
            ['email' => 'admin@dinasalon.com'],
            [
                'name'       => 'Admin Dina Salon',
                'email'      => 'admin@dinasalon.com',
                'password'   => Hash::make('admin123'),
                'role'       => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // OWNER
        DB::table('users')->updateOrInsert(
            ['email' => 'owner@dinasalon.com'],
            [
                'name'       => 'Owner Dina Salon',
                'email'      => 'owner@dinasalon.com',
                'password'   => Hash::make('owner123'),
                'role'       => 'owner',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}