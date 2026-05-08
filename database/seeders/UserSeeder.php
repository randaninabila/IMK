<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Branch;
use App\Models\SpecialistProfile;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $branchA = Branch::where('name', 'Laudendang')->first();
        $branchB = Branch::where('name', 'Tuasan')->first();

        // OWNER
        User::updateOrCreate(
            ['email' => 'owner@dinasalon.com'],
            ['name' => 'Owner Dina Salon', 'password' => Hash::make('owner123'), 'role' => 'owner', 'branch_id' => null]
        );

        // ADMIN cabang A
        User::updateOrCreate(
            ['email' => 'admin.laudendang@dinasalon.com'],
            ['name' => 'Admin Laudendang', 'password' => Hash::make('admin123'), 'role' => 'admin', 'branch_id' => $branchA->id]
        );

        // ADMIN cabang B
        User::updateOrCreate(
            ['email' => 'admin.tuasan@dinasalon.com'],
            ['name' => 'Admin Tuasan', 'password' => Hash::make('admin123'), 'role' => 'admin', 'branch_id' => $branchB->id]
        );

        // SPECIALIST cabang A (7 orang)
        $specialistsA = [
            ['name' => 'Siti Aminah',   'email' => 'siti@dinasalon.com',    'specialty' => 'Hair Treatment'],
            ['name' => 'Rina Marlina',  'email' => 'rina@dinasalon.com',    'specialty' => 'Facial'],
            ['name' => 'Dewi Putri',    'email' => 'dewi@dinasalon.com',    'specialty' => 'Nail Polish'],
            ['name' => 'Fitri Handayani','email' => 'fitri@dinasalon.com',  'specialty' => 'Waxing'],
            ['name' => 'Nadia Sari',    'email' => 'nadia@dinasalon.com',   'specialty' => 'Hair Color'],
            ['name' => 'Lestari Wulan', 'email' => 'lestari@dinasalon.com', 'specialty' => 'Scalp Treatment'],
            ['name' => 'Yuni Astuti',   'email' => 'yuni@dinasalon.com',    'specialty' => 'Skin Care'],
        ];

        foreach ($specialistsA as $s) {
            $user = User::updateOrCreate(
                ['email' => $s['email']],
                ['name' => $s['name'], 'password' => Hash::make('specialist123'), 'role' => 'specialist', 'branch_id' => $branchA->id]
            );
            SpecialistProfile::updateOrCreate(
                ['user_id' => $user->id],
                ['specialty' => $s['specialty']]
            );
        }

        // SPECIALIST cabang B (5 orang)
        $specialistsB = [
            ['name' => 'Rani Kusuma',   'email' => 'rani@dinasalon.com',    'specialty' => 'Facial'],
            ['name' => 'Mega Pertiwi',  'email' => 'mega@dinasalon.com',    'specialty' => 'Hair Treatment'],
            ['name' => 'Intan Permata', 'email' => 'intan@dinasalon.com',   'specialty' => 'Nail Polish'],
            ['name' => 'Suci Rahayu',   'email' => 'suci@dinasalon.com',    'specialty' => 'Waxing'],
            ['name' => 'Dian Novita',   'email' => 'dian@dinasalon.com',    'specialty' => 'Skin Care'],
        ];

        foreach ($specialistsB as $s) {
            $user = User::updateOrCreate(
                ['email' => $s['email']],
                ['name' => $s['name'], 'password' => Hash::make('specialist123'), 'role' => 'specialist', 'branch_id' => $branchB->id]
            );
            SpecialistProfile::updateOrCreate(
                ['user_id' => $user->id],
                ['specialty' => $s['specialty']]
            );
        }

        // CUSTOMER
        User::updateOrCreate(
            ['email' => 'customer@dinasalon.com'],
            [
                'name' => 'Customer Dina',
                'password' => Hash::make('customer123'),
                'role' => 'customer',
                'branch_id' => null,
            ]
        );
    }
}
