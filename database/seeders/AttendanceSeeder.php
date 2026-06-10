<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attendance;
use App\Models\Intern;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $interns = Intern::all();
        
        if ($interns->isEmpty()) {
            $this->command->warn('Tidak ada data intern. Jalankan InternSeeder terlebih dahulu.');
            return;
        }

        $statuses = ['hadir', 'izin', 'sakit', 'alfa'];
        
        foreach ($interns as $intern) {
            // Generate attendance untuk 30 hari terakhir
            for ($i = 0; $i < 30; $i++) {
                $date = Carbon::now()->subDays($i);
                $status = $statuses[array_rand($statuses)];
                
                $attributes = [
                    'status' => $status,
                ];

                // Jika hadir, tambahkan check in/out dan foto
                if ($status === 'hadir') {
                    $attributes['check_in'] = $date->copy()->setTime(8, rand(0, 30), 0);
                    $attributes['check_out'] = $date->copy()->setTime(16, rand(0, 59), 0);
                    $attributes['photo_path'] = 'dummy/attendance_' . uniqid() . '.jpg';
                    $attributes['photo_checkout'] = 'dummy/checkout_' . uniqid() . '.jpg';
                }

                // Jika izin atau sakit, tambahkan note dan dokumen
                if (in_array($status, ['izin', 'sakit'])) {
                    $attributes['note'] = 'Alasan ' . $status . ' - ' . fake()->sentence();
                    $attributes['document_path'] = 'dummy/document_' . uniqid() . '.pdf';
                    $attributes['document_status'] = ['pending', 'approved', 'rejected'][rand(0, 2)];
                    $attributes['admin_note'] = rand(0, 1) ? fake()->sentence() : null;
                }

                Attendance::updateOrCreate(
                    ['intern_id' => $intern->id, 'date' => $date->format('Y-m-d')],
                    $attributes
                );
            }
        }

        $this->command->info('✅ Attendance seeder berhasil dijalankan!');
    }
}