<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            RoleAndPermissionSeeder::class,
        ]);

        /* super admin utama */
        $superAdmin = User::updateOrCreate(
            ['email' => 'superadmin@simagang.com'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('password123'),
            ]
        );
        $superAdmin->syncRoles(['super_admin']);

        $this->command->info('Super admin created');
        $this->command->info('Email: superadmin@simagang.com');
        $this->command->info('Password: password123');

        /* admin full access */
        $admin = User::updateOrCreate(
            ['email' => 'admin@simagang.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password123'),
            ]
        );
        $admin->syncRoles(['admin_full']);

        $this->command->info('Admin full access created');
        $this->command->info('Email: admin@simagang.com');
        $this->command->info('Password: password123');

        User::where('email', 'admin2@simagang.com')->delete();
    }
}
