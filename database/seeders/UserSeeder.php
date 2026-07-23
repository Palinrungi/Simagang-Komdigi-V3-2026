<?php

namespace Database\Seeders;

use App\Models\Institusi;
use App\Models\Intern;
use App\Models\Mentor;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->resetSeedableTables();

        $this->call([
            RoleAndPermissionSeeder::class,
        ]);


        /*
        |--------------------------------------------------------------------------
        | ADMIN USER
        |--------------------------------------------------------------------------
        */

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


        /*
        |--------------------------------------------------------------------------
        | RESET DATA MENTOR
        |--------------------------------------------------------------------------
        */
        Intern::query()->update(['mentor_id' => null]);
        User::where('role', 'mentor')->delete();
        Mentor::query()->delete();


        /*
        |--------------------------------------------------------------------------
        | SEED MENTOR + USER MENTOR
        |--------------------------------------------------------------------------
        */
        $mentorNames = [
            'Rudy Hermayadi',
            'Tasmil',
            'Herman',
            'Yayat Dendy Hadiyat',
            'Bahrawi',
            'Azwar Azis',
            'Olga Olivia Sombolayuk',
            'Nur Alam',
            'Farhan Rafsanjani',
            'Harbedy Hadya Tina',
            'Solehuddin Hasdin',
            'Muhammad Lutfi Sulthon Auliya S.',
            'Nur Fajriani Hipta',
            'Kalashnikov',
            'Fadly Aprianto',
            'Sukmawati',
            'Nuraeni Yuliati',
            'Rizqan Halalah MZ',
            'Muhammad Irfan',
            'Pierre Caesar Assyurah Tendean',
            'Muhammad Azham Subhan',
            'Idhil Cahyo Diputera',
            'Fairuz',
            'Muh. Andar Sugianto',
            'Rida Sulistiana Agustin',
            'Muhammad Harun Ashar',
            'Muhammad Agung',
        ];

        $mentors = collect($mentorNames)->map(function ($name) {

            $email = Str::slug($name, '.') . '@komdigi.com';

            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make('password123'),
            ]);
            $user->syncRoles(['mentor']);

            return Mentor::create([
                'name' => $name,
                'email' => $email,
                'position' => null,
                'phone' => null,
                'is_active' => true,
                'user_id' => $user->id,
            ]);
        });

        $this->command->info('Mentor users created: ' . $mentors->count());
        $this->command->info('Default mentor password: password123');

        /*
        |--------------------------------------------------------------------------
        | SEED INSTITUTION + USER INSTITUTION
        |--------------------------------------------------------------------------
        */
        $institutions = [
            'Universitas Hasanuddin',
            'Universitas Negeri Makassar',
            'Universitas DIPA Makassar',
            'Universitas Islam Negeri Alauddin Makassar',
            'Universitas Bosowa',
            'Universitas Sulawesi Barat',
            'Universitas Multimedia Nusantara',
            'SMK Telkom Makassar',
            'SMK Handayani Makassar',
            'SMK Negeri 5 Gowa',
        ];

        $institutionUsers = collect($institutions)->map(function ($institution, $index) {
            $email = Str::slug($institution, '.') . '@simagang.com';
            $jenisInstitusi = Str::startsWith($institution, 'SMK') ? 'sekolah' : 'kampus';

            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $institution,
                    'password' => Hash::make('password123'),
                ]
            );

            Institusi::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nama_institusi' => $institution,
                    'jenis_institusi' => $jenisInstitusi,
                    'nomor_identitas' => 'INST-' . str_pad((string) ($index + 1), 3, '0', STR_PAD_LEFT),
                    'no_hp' => '081200000' . str_pad((string) ($index + 1), 3, '0', STR_PAD_LEFT),
                    'fakultas' => null,
                    'departemen' => null,
                ]
            );

            return $user;
        });

        $this->command->info('Institution users created: ' . $institutionUsers->count());
        $this->command->info('Default institution password: password123');

        /*
        |--------------------------------------------------------------------------
        | SAMPLE INTERN USERS
        |--------------------------------------------------------------------------
        */
        $interns = [
            [
                'name' => 'Ahmad Fauzi',
                'email' => 'ahmad.fauzi@example.com',
                'gender' => 'Laki-laki',
                'education_level' => 'S1/D4',
                'major' => 'Teknik Informatika',
                'phone' => '081234567890',
                'institution' => 'Universitas Contoh',
                'start_date' => now()->subDays(30),
                'end_date' => now()->addDays(60),
            ],
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'siti.nurhaliza@example.com',
                'gender' => 'Perempuan',
                'education_level' => 'SMA/SMK',
                'major' => 'Rekayasa Perangkat Lunak',
                'phone' => '089876543210',
                'institution' => 'SMK Teknologi',
                'start_date' => now()->subDays(20),
                'end_date' => now()->addDays(70),
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@example.com',
                'gender' => 'Laki-laki',
                'education_level' => 'S1/D4',
                'major' => 'Sistem Informasi',
                'phone' => '081122334455',
                'institution' => 'Universitas Negeri',
                'start_date' => now()->subDays(10),
                'end_date' => now()->addDays(80),
            ],
            [
                'name' => 'Rina Putri Handoko',
                'email' => 'rina.putri@example.com',
                'gender' => 'Perempuan',
                'education_level' => 'S1/D4',
                'major' => 'Teknologi Informasi',
                'phone' => '087765432109',
                'institution' => 'Universitas Gadjah Mada',
                'purpose' => 'Pengembangan Sistem Informasi',
                'team' => 'Backend Development',
                'start_date' => now()->subDays(60),
                'end_date' => now()->addDays(30), // Selesai bulan 3
            ],
            [
                'name' => 'Dimas Wijaya',
                'email' => 'dimas.wijaya@example.com',
                'gender' => 'Laki-laki',
                'education_level' => 'S1/D4',
                'major' => 'Informatika',
                'phone' => '081987654321',
                'institution' => 'Institut Teknologi Bandung',
                'purpose' => 'Pengembangan Mobile App',
                'team' => 'Mobile Development',
                'start_date' => now()->subDays(45),
                'end_date' => now()->addDays(45), // Selesai bulan 3
            ],
            [
                'name' => 'Lestari Wijaya',
                'email' => 'lestari.wijaya@example.com',
                'gender' => 'Perempuan',
                'education_level' => 'SMA/SMK',
                'major' => 'Teknik Komputer dan Jaringan',
                'phone' => '082112233445',
                'institution' => 'SMK Muhammadiyah',
                'purpose' => 'Praktik Teknis Jaringan',
                'team' => 'Infrastructure',
                'start_date' => now()->subDays(30),
                'end_date' => now()->addDays(55), // Selesai bulan 3
            ],
        ];

        foreach ($interns as $index => $internData) {

            $user = User::updateOrCreate(
                ['email' => $internData['email']],
                [
                    'name' => $internData['name'],
                    'password' => Hash::make('password123'),
                ]
            );
            $user->syncRoles(['intern']);

            $mentor = $mentors->count() > 0
                ? $mentors[$index % $mentors->count()]
                : null;

            Intern::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'gender' => $internData['gender'],
                    'education_level' => $internData['education_level'],
                    'major' => $internData['major'],
                    'phone' => $internData['phone'],
                    'institution' => $internData['institution'],
                    'mentor_id' => $mentor?->id,
                    'start_date' => $internData['start_date'],
                    'end_date' => $internData['end_date'],
                    'photo_path' => null,
                    'is_active' => true,
                ]
            );
        }

        $this->command->info('Sample intern users created');
        $this->command->info('Default intern password: password123');
    }

    private function resetSeedableTables(): void
    {
        $tables = [
            'model_has_permissions',
            'model_has_roles',
            'role_has_permissions',
            'permissions',
            'roles',
            'institusis',
            'interns',
            'mentors',
            'users',
        ];

        Schema::disableForeignKeyConstraints();

        try {
            foreach ($tables as $table) {
                if (Schema::hasTable($table)) {
                    DB::table($table)->truncate();
                }
            }
        } finally {
            Schema::enableForeignKeyConstraints();
        }
    }
}