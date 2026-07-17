<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Intern;
use Illuminate\Support\Facades\Hash;

class AddNewUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin_baru@simagang.com'],
            [
                'name' => 'Admin Baru',
                'password' => Hash::make('password')
            ]
        );
        $roleSuperAdmin = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        if (!$admin->hasRole('super_admin')) {
            $admin->assignRole('super_admin');
        }

        // 2. Create Intern (Bintang)
        $internUser = User::firstOrCreate(
            ['email' => 'bintang@simagang.com'],
            [
                'name' => 'bintang',
                'password' => Hash::make('password')
            ]
        );
        if (!$internUser->hasRole('intern')) {
            $internUser->assignRole('intern');
        }

        // Create Intern Profile
        Intern::firstOrCreate(
            ['user_id' => $internUser->id],
            [
                'gender' => 'Laki-laki',
                'institution' => 'Universitas',
                'phone' => '08123456789',
                'start_date' => now(),
                'end_date' => now()->addMonths(3),
                'is_active' => true
            ]
        );
    }
}
