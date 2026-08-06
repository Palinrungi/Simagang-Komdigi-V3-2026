<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Industri;

class IndustriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdminQuery = User::role('super_admin')
            ->orderBy('id');

        $jumlahSuperAdmin = $superAdminQuery->count();

        if ($jumlahSuperAdmin === 0) {
            $this->command->error(
                'Seeder dibatalkan: User dengan role super_admin tidak ditemukan.'
            );

            return;
        }

        if ($jumlahSuperAdmin > 1) {
            $this->command->warn(
                "Ditemukan {$jumlahSuperAdmin} user super_admin. Seeder akan menggunakan user dengan ID terkecil."
            );
        }

        $superAdmin = $superAdminQuery->first();

        Industri::updateOrCreate(
            [
                'user_id' => $superAdmin->id,
            ],
            [
                'nama_industri' => 'BBLSDM Komdigi Makassar',

                // Simpan path file, bukan asset()
                'logo_industri' => 'vendor/logo_komdigi.jpeg',

                'bidang_industri' => 'Teknologi Informasi, Komunikasi dan Digital',

                'jenis_lembaga' => 'pemerintah',

                'deskripsi_industri' => 'Balai Besar Pelatihan Sumber Daya Manusia dan Penelitian Komunikasi dan Digital (BBLSDM Komdigi Makassar) merupakan Unit Pelaksana Teknis di lingkungan Kementerian Komunikasi dan Digital yang berfokus pada Pelatihan kompetensi sumber daya manusia di bidang komunikasi, informatika, dan transformasi digital. BBLSDM Komdigi Makassar menyelenggarakan berbagai program pelatihan, sertifikasi, penelitian, serta program magang untuk mendukung peningkatan kualitas talenta digital Indonesia.',

                'alamat_industri' => 'Jl. Prof. Dr. Abdurrahman Basalamah II No.25',

                'kota_kabupaten' => 'Kota Makassar',

                'email_industri' => '-',

                'nomor_telepon_industri' => '0411-4660084',

                'nib' => '-',
            ]
        );

        $this->command->info(
            "Profil BBLSDM Komdigi Makassar berhasil dibuat untuk user ID {$superAdmin->id}."
        );
    }
}