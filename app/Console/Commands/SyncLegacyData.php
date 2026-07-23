<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Spatie\Permission\Models\Role;

class SyncLegacyData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'simagang:sync-legacy-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sinkronisasi data role dan nama intern dari database lama sebelum migrasi';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai sinkronisasi data dari database lama...');

        // --- 1. SINKRONISASI ROLE SPATIE ---
        $this->info('1. Menyinkronkan Role User...');
        $users = User::all();
        $roleCount = 0;

        foreach ($users as $user) {
            // Kita gunakan DB Facade agar aman membaca kolom 'role' lama
            // berjaga-jaga jika Model User sudah tidak mengenalinya.
            $legacyUser = DB::table('users')->where('id', $user->id)->first();

            if ($legacyUser && isset($legacyUser->role) && !empty($legacyUser->role)) {
                $roleName = $legacyUser->role;
                
                // Buat Role secara otomatis jika belum ada di database
                Role::findOrCreate($roleName, 'web');

                // Berikan Spatie Role sesuai role lama
                if (!$user->hasRole($roleName)) {
                    $user->assignRole($roleName);
                    $roleCount++;
                }
            }
        }
        $this->info("Berhasil menyinkronkan role untuk {$roleCount} user.");

        // --- 2. SINKRONISASI NAMA INTERN ---
        $this->info('2. Menyinkronkan Nama Intern...');
        // Menggunakan DB facade karena kolom 'name' akan segera dihapus dari Model
        $interns = DB::table('interns')->get();
        $nameCount = 0;

        foreach ($interns as $intern) {
            if (isset($intern->name) && !empty($intern->name)) {
                // Pindahkan nama dari interns.name ke users.name
                DB::table('users')
                    ->where('id', $intern->user_id)
                    ->update(['name' => $intern->name]);
                $nameCount++;
            }
        }
        $this->info("Berhasil menyinkronkan nama untuk {$nameCount} intern.");

        $this->info('✅ SINKRONISASI SELESAI!');
        $this->info('Sekarang Anda aman untuk menjalankan "php artisan migrate"');
    }
}