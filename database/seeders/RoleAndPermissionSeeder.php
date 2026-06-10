<?php
// database/seeders/RoleAndPermissionSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        // ============================================
        // DEFINE ALL PERMISSIONS
        // ============================================
        
        // USER MANAGEMENT PERMISSIONS
        $permissions = [
            // User Management
            'view_users' => 'Melihat daftar user (Intern dan Mentor)',
            'create_users' => 'Membuat user baru (Intern dan Mentor)',
            'edit_users' => 'Mengedit user (Intern dan Mentor)',
            'delete_users' => 'Menghapus user (Intern dan Mentor)',

            // Admin Management
            'view_admins' => 'Melihat daftar Admin',
            'create_admin' => 'Membuat Admin baru (dengan assign aksesibilitas)',
            'edit_admin' => 'Mengedit Admin (update akses)',
            'delete_admin' => 'Menghapus Admin (hanya Super Admin)',

            // Mentor Management
            'view_mentors' => 'Melihat daftar Mentor',
            'manage_mentors' => 'Kelola data Mentor (CRUD)',

            // Attendance Permissions
            'view_attendance' => 'Melihat data Absensi',
            'manage_attendance' => 'Kelola Absensi (Approve/Reject)',

            // Logbook Permissions
            'view_logbook' => 'Melihat data Logbook',
            'manage_logbook' => 'Kelola Logbook (Approve/Reject)',

            // Report Permissions
            'view_reports' => 'Melihat Laporan Akhir',
            'manage_reports' => 'Kelola Laporan Akhir (Approve/Reject/Score)',

            // Lowongan Management
            'view_lowongan' => 'Melihat daftar Lowongan Magang',
            'manage_lowongan' => 'Kelola data Lowongan Magang (CRUD)',

            // Verifikasi Lowongan
            'view_verifikasi_lowongan' => 'Melihat daftar Lowongan Magang untuk diverifikasi',

            // Pengajuan Management
            'manage_pengajuan' => 'Kelola Pengajuan Calon Anak Magang (Approve/Reject/Revision)',

            // Intern Management
            'view_interns' => 'Melihat daftar Intern',
            'manage_interns' => 'Kelola data Intern (CRUD)',

            // Tim Management
            'view_teams' => 'Melihat daftar Tim',
            'manage_teams' => 'Kelola data Tim (CRUD)',

            // Dashboard Access
            'access_admin_dashboard' => 'Akses Admin Dashboard',
        ];

        // Create all permissions
        foreach ($permissions as $permission => $description) {
            Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'web']
            );
        }

        // ============================================
        // DEFINE ROLES
        // ============================================

        // SUPER ADMIN - Full Access
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $superAdminRole->syncPermissions(Permission::all());

        // ADMIN FULL ACCESS
        $adminFullRole = Role::firstOrCreate(['name' => 'admin_full', 'guard_name' => 'web']);
        $adminFullRole->syncPermissions([
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',
            'view_mentors',
            'manage_mentors',
            'view_interns',
            'manage_interns',
            'view_attendance',
            'manage_attendance', 
            'view_logbook',
            'manage_logbook',
            'view_reports',
            'manage_reports',
            'manage_pengajuan',
            'view_teams',
            'manage_teams',
            'access_admin_dashboard',
            'view_lowongan',
            'manage_lowongan',
            'view_verifikasi_lowongan',
        ]);

        // ADMIN USER MANAGER - Khusus kelola user
        $adminUserManagerRole = Role::firstOrCreate(['name' => 'admin_user_manager', 'guard_name' => 'web']);
        $adminUserManagerRole->syncPermissions([
            'view_users',
            'view_interns',
            'manage_interns',
            'view_mentors',
            'manage_mentors',
            'view_attendance',
            'view_logbook',
            'view_reports',
            'access_admin_dashboard',
        ]);

        // ADMIN DATA MANAGER - Kelola data anak magang
        $adminDataManagerRole = Role::firstOrCreate(['name' => 'admin_data_manager', 'guard_name' => 'web']);
        $adminDataManagerRole->syncPermissions([
            'view_interns',
            'manage_interns',
            'view_attendance',
            'manage_attendance',
            'view_logbook',
            'manage_logbook',
            'view_reports',
            'manage_reports',
            'access_admin_dashboard',
        ]);

        // INTERN ROLE
        $internRole = Role::firstOrCreate(['name' => 'intern', 'guard_name' => 'web']);
        // Intern permissions bisa ditambahkan sesuai kebutuhan

        // MENTOR ROLE
        $mentorRole = Role::firstOrCreate(['name' => 'mentor', 'guard_name' => 'web']);
        // Mentor permissions bisa ditambahkan sesuai kebutuhan

        // INSTITUSI ROLE
        $institusiRole = Role::firstOrCreate(['name' => 'institusi', 'guard_name' => 'web']);
        // Role institusi belum diberi permission khusus

        // INDUSTRI ROLE
        $this->command->info('✅ Roles dan Permissions berhasil dibuat!');
    }
}